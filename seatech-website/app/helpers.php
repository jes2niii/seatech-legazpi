<?php

use App\Models\Category;
use App\Models\Certificate;
use App\Models\CoreValue;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Facility;
use App\Models\NewsPost;
use App\Models\SiteSetting;
use App\Models\Student;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TrainingSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        $all = SiteSetting::getAllCached();

        if (! array_key_exists($key, $all)) {
            return $default;
        }

        $value = $all[$key];

        if ($value === null) {
            return $default;
        }

        $row = DB::table('site_settings')
            ->where('key', $key)
            ->first();

        if (! $row) {
            return $value;
        }

        return match ($row->type) {
            'int', 'integer' => (int) $value,
            'bool', 'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            'float' => (float) $value,
            default => $value,
        };
    }
}

if (! function_exists('setting_group')) {
    function setting_group(string $prefix): array
    {
        $all = SiteSetting::getAllCached();
        $result = [];
        $prefixWithDot = $prefix.'.';

        foreach ($all as $key => $value) {
            if (str_starts_with($key, $prefixWithDot)) {
                $shortKey = substr($key, strlen($prefixWithDot));
                $result[$shortKey] = $value;
            }
        }

        return $result;
    }
}

if (! function_exists('format_rich_text')) {
    /**
     * Convert plain-text into safe HTML.
     *
     * Lines starting with "- ", "* ", or "• " become <ul><li> items.
     * A list with only one item is rendered as a <p> instead of <ul>.
     * Blank lines separate paragraphs.
     * All output is HTML-escaped (XSS-safe).
     */
    function format_rich_text(?string $text): string
    {
        if ($text === null || trim($text) === '') {
            return '';
        }

        $html = '';
        $inList = false;
        $listItems = [];
        $paragraphLines = [];

        $flushParagraph = function () use (&$paragraphLines, &$html) {
            if (! empty($paragraphLines)) {
                $p = e(implode(' ', $paragraphLines));
                $html .= "<p class=\"mb-3 leading-relaxed\">{$p}</p>\n";
                $paragraphLines = [];
            }
        };

        $closeList = function () use (&$inList, &$html, &$listItems) {
            if ($inList) {
                if (count($listItems) === 1) {
                    $html .= '<p class="mb-3 leading-relaxed">'.e($listItems[0])."</p>\n";
                } else {
                    $html .= "<ul class=\"list-disc pl-6 space-y-1 mb-4\">\n";
                    foreach ($listItems as $item) {
                        $html .= '  <li>'.e($item)."</li>\n";
                    }
                    $html .= "</ul>\n";
                }
                $inList = false;
                $listItems = [];
            }
        };

        $lines = preg_split('/\R/u', $text);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                $flushParagraph();
                $closeList();

                continue;
            }
            if (preg_match('/^[-*•]\s+(.+)$/u', $trimmed, $m)) {
                $flushParagraph();
                $inList = true;
                $listItems[] = $m[1];
            } else {
                $closeList();
                $paragraphLines[] = $trimmed;
            }
        }
        $flushParagraph();
        $closeList();

        return trim($html);
    }
}

if (! function_exists('core_value_icon')) {
    /**
     * Return the SVG path markup for a preset core value icon name.
     */
    function core_value_icon(string $name): string
    {
        $paths = [
            'star' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.299.921-.755 1.688-1.539 1.118l-3.36-2.448a1 1 0 00-1.175 0l-3.371 2.448c-.783.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>',
            'heart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
            'shield' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
            'check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
            'lightbulb' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
            'handshake' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 6l-4-4-4 4m8 0v6m0 0l4 4-4 4m4-4H6m12 0v-6m0 0l-4-4 4-4M6 12l4-4"/>',
            'compass' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16z" fill="currentColor"/>',
            'anchor' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><circle cx="12" cy="5" r="3" fill="currentColor"/>',
            'clock' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'sliders' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>',
            'shield-alert' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"/>',
        ];

        $path = $paths[$name] ?? $paths['star'];

        return '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">'.$path.'</svg>';
    }
}

if (! function_exists('activity_field_map')) {
    /**
     * Centralized map of activity log name => human label + value format.
     *
     * Supported format tokens:
     *   - "string"          : cast to string
     *   - "int"             : integer
     *   - "currency:PHP"    : formatted as ₱4,200.00
     *   - "date"            : M d, Y (null becomes "—")
     *   - "datetime"        : M d, Y h:i A (null becomes "—")
     *   - "bool:Yes,No"     : 1/true => first, 0/false => second
     *   - "enum:a=A,b=B,...": value lookup
     *   - "fk:ModelClass"   : resolves an ID to a related record's display string
     *   - "user"            : alias for fk:User (resolved to name)
     *
     * Set a field to null to hide it from the activity log UI.
     */
    function activity_field_map(): array
    {
        return [
            'course' => [
                'code' => ['label' => 'Course Code', 'format' => 'string'],
                'title' => ['label' => 'Title', 'format' => 'string'],
                'slug' => null,
                'category_id' => ['label' => 'Category', 'format' => 'fk:Category'],
                'description' => ['label' => 'Description', 'format' => 'string'],
                'duration' => ['label' => 'Duration', 'format' => 'string'],
                'fee' => ['label' => 'Fee', 'format' => 'currency:PHP'],
                'prerequisites' => ['label' => 'Prerequisites', 'format' => 'string'],
                'learning_outcomes' => ['label' => 'Learning Outcomes', 'format' => 'string'],
                'max_participants' => ['label' => 'Max Participants', 'format' => 'int'],
                'is_active' => ['label' => 'Status', 'format' => 'bool:Active,Inactive'],
                'archived_at' => ['label' => 'Archive Date', 'format' => 'datetime'],
            ],
            'schedule' => [
                'course_id' => ['label' => 'Course', 'format' => 'fk:Course'],
                'instructor_id' => ['label' => 'Instructor', 'format' => 'fk:User'],
                'start_date' => ['label' => 'Start Date', 'format' => 'date'],
                'end_date' => ['label' => 'End Date', 'format' => 'date'],
                'registration_deadline' => ['label' => 'Registration Deadline', 'format' => 'date'],
                'venue' => ['label' => 'Venue', 'format' => 'string'],
                'capacity' => ['label' => 'Capacity', 'format' => 'int'],
                'enrolled_count' => ['label' => 'Enrolled', 'format' => 'int'],
                'status' => ['label' => 'Schedule Status', 'format' => 'enum:upcoming=Upcoming,ongoing=Ongoing,completed=Completed,cancelled=Cancelled'],
            ],
            'enrollment' => [
                'student_id' => ['label' => 'Student', 'format' => 'fk:Student'],
                'training_schedule_id' => ['label' => 'Training Schedule', 'format' => 'fk:TrainingSchedule'],
                'status' => ['label' => 'Enrollment Status', 'format' => 'enum:pending=Pending,approved=Approved,rejected=Rejected,completed=Completed,cancelled=Cancelled'],
                'payment_status' => ['label' => 'Payment Status', 'format' => 'enum:unpaid=Unpaid,partial=Partial,paid=Paid,refunded=Refunded'],
                'notes' => ['label' => 'Notes', 'format' => 'string'],
                'approved_by' => ['label' => 'Approved By', 'format' => 'fk:User'],
            ],
            'certificate' => [
                'enrollment_id' => ['label' => 'Enrollment', 'format' => 'fk:Enrollment'],
                'student_id' => ['label' => 'Student', 'format' => 'fk:Student'],
                'course_id' => ['label' => 'Course', 'format' => 'fk:Course'],
                'certificate_number' => ['label' => 'Certificate Number', 'format' => 'string'],
                'issued_date' => ['label' => 'Issued Date', 'format' => 'date'],
                'is_verified' => ['label' => 'Verified', 'format' => 'bool:Yes,No'],
            ],
            'news' => [
                'title' => ['label' => 'Title', 'format' => 'string'],
                'is_published' => ['label' => 'Published', 'format' => 'bool:Yes,No'],
                'published_at' => ['label' => 'Published At', 'format' => 'datetime'],
                'author_id' => ['label' => 'Author', 'format' => 'fk:User'],
            ],
            'testimonial' => [
                'student_name' => ['label' => 'Student Name', 'format' => 'string'],
                'course_taken' => ['label' => 'Course Taken', 'format' => 'string'],
                'rating' => ['label' => 'Rating', 'format' => 'int'],
                'is_active' => ['label' => 'Active', 'format' => 'bool:Yes,No'],
            ],
            'facility' => [
                'name' => ['label' => 'Name', 'format' => 'string'],
                'is_active' => ['label' => 'Active', 'format' => 'bool:Yes,No'],
                'sort_order' => ['label' => 'Sort Order', 'format' => 'int'],
            ],
            'team' => [
                'name' => ['label' => 'Name', 'format' => 'string'],
                'position' => ['label' => 'Position', 'format' => 'string'],
                'department' => ['label' => 'Department', 'format' => 'string'],
                'is_active' => ['label' => 'Active', 'format' => 'bool:Yes,No'],
            ],
            'core_value' => [
                'name' => ['label' => 'Name', 'format' => 'string'],
                'is_active' => ['label' => 'Active', 'format' => 'bool:Yes,No'],
            ],
            'category' => [
                'name' => ['label' => 'Name', 'format' => 'string'],
                'is_active' => ['label' => 'Active', 'format' => 'bool:Yes,No'],
            ],
        ];
    }
}

if (! function_exists('activity_field_config')) {
    /**
     * Get the field config for a given log name + field, or null if the
     * field should be hidden from the UI entirely.
     */
    function activity_field_config(string $logName, string $field): ?array
    {
        $map = activity_field_map();
        if (! isset($map[$logName])) {
            return null;
        }
        $entry = $map[$logName][$field] ?? null;
        if ($entry === null) {
            return null;
        }

        return $entry;
    }
}

if (! function_exists('activity_field_label')) {
    /**
     * Human label for a log field. Returns null when the field is hidden.
     */
    function activity_field_label(string $logName, string $field): ?string
    {
        $cfg = activity_field_config($logName, $field);

        return $cfg['label'] ?? null;
    }
}

if (! function_exists('activity_format_value')) {
    /**
     * Format a single field value for human display. Unknown formats fall
     * back to a plain string cast.
     */
    function activity_format_value(string $logName, string $field, mixed $value): string
    {
        if ($value === null) {
            return '—';
        }

        $cfg = activity_field_config($logName, $field);
        $format = $cfg['format'] ?? 'string';

        // Token parsing
        if (str_starts_with($format, 'currency:')) {
            $currency = substr($format, strlen('currency:'));
            $num = is_numeric($value) ? (float) $value : 0.0;
            $symbol = $currency === 'PHP' ? '₱' : $currency.' ';

            return $symbol.number_format($num, 2);
        }

        if (str_starts_with($format, 'enum:')) {
            $pairs = [];
            foreach (explode(',', substr($format, strlen('enum:'))) as $pair) {
                [$k, $v] = array_pad(explode('=', $pair, 2), 2, null);
                if ($k !== null && $v !== null) {
                    $pairs[trim($k)] = trim($v);
                }
            }
            $key = (string) $value;

            return $pairs[$key] ?? ucfirst($key);
        }

        if (str_starts_with($format, 'bool:')) {
            $labels = explode(',', substr($format, strlen('bool:')), 2);
            $truthy = $labels[0] ?? 'Yes';
            $falsy = $labels[1] ?? 'No';
            $bool = is_string($value)
                ? in_array(strtolower($value), ['1', 'true', 'on', 'yes'], true)
                : (bool) $value;

            return $bool ? $truthy : $falsy;
        }

        if (str_starts_with($format, 'fk:')) {
            $class = substr($format, strlen('fk:'));

            return activity_resolve_fk($class, $value) ?? (string) $value;
        }

        return match ($format) {
            'int' => (string) (int) $value,
            'date' => format_activity_date($value),
            'datetime' => format_activity_datetime($value),
            default => is_scalar($value) ? (string) $value : json_encode($value),
        };
    }
}

if (! function_exists('format_activity_date')) {
    function format_activity_date(mixed $value): string
    {
        try {
            return Carbon::parse($value)->format('M d, Y');
        } catch (Throwable $e) {
            return (string) $value;
        }
    }
}

if (! function_exists('format_activity_datetime')) {
    function format_activity_datetime(mixed $value): string
    {
        try {
            return Carbon::parse($value)->format('M d, Y h:i A');
        } catch (Throwable $e) {
            return (string) $value;
        }
    }
}

if (! function_exists('activity_resolve_fk')) {
    /**
     * Resolve a foreign-key id to a related record's display string.
     * Returns null if the model can't be loaded.
     */
    function activity_resolve_fk(string $class, mixed $id): ?string
    {
        if ($id === null || $id === '') {
            return null;
        }

        $model = match ($class) {
            'User' => User::class,
            'Course' => Course::class,
            'Category' => Category::class,
            'TrainingSchedule' => TrainingSchedule::class,
            'Enrollment' => Enrollment::class,
            'Student' => Student::class,
            'Certificate' => Certificate::class,
            'NewsPost' => NewsPost::class,
            'Testimonial' => Testimonial::class,
            'Facility' => Facility::class,
            'TeamMember' => TeamMember::class,
            'CoreValue' => CoreValue::class,
            default => null,
        };

        if ($model === null) {
            return null;
        }

        $record = $model::find($id);
        if (! $record) {
            return null;
        }

        return match (true) {
            method_exists($record, 'getAttribute') && $record->getAttribute('name') => $record->name,
            isset($record->title) => $record->title,
            isset($record->certificate_number) => $record->certificate_number,
            default => '#'.$record->getKey(),
        };
    }
}

if (! function_exists('activity_log_type')) {
    /**
     * Friendly display name for a log_name (e.g. "course" => "Course").
     */
    function activity_log_type(string $logName): string
    {
        return match ($logName) {
            'course' => 'Course',
            'schedule' => 'Training Schedule',
            'enrollment' => 'Enrollment',
            'certificate' => 'Certificate',
            'news' => 'News Article',
            'testimonial' => 'Testimonial',
            'facility' => 'Facility',
            'team' => 'Team Member',
            'core_value' => 'Core Value',
            'category' => 'Category',
            default => ucfirst(str_replace('_', ' ', $logName)),
        };
    }
}

if (! function_exists('activity_event_label')) {
    /**
     * Friendly verb for an event name.
     */
    function activity_event_label(?string $event): string
    {
        return match ($event) {
            'created' => 'created',
            'updated' => 'updated',
            'deleted' => 'deleted',
            'restored' => 'restored',
            default => $event ? str_replace('_', ' ', $event) : 'changed',
        };
    }
}

if (! function_exists('activity_causer_name')) {
    /**
     * Returns the display name for the causer (user) of an activity.
     */
    function activity_causer_name(?Activity $activity): string
    {
        if (! $activity || ! $activity->causer) {
            return 'System';
        }

        return $activity->causer->name ?? 'User #'.$activity->causer_id;
    }
}

if (! function_exists('activity_causer_initials')) {
    function activity_causer_initials(?Activity $activity): string
    {
        $name = activity_causer_name($activity);
        $parts = preg_split('/\s+/u', trim($name)) ?: [];
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : '?';
    }
}

if (! function_exists('activity_subject_label')) {
    /**
     * Human label for the subject of an activity. Falls back to the
     * snapshot stored in properties (e.g. for deleted records).
     */
    function activity_subject_label(?Activity $activity): ?string
    {
        if (! $activity) {
            return null;
        }

        if ($activity->subject) {
            $record = $activity->subject;

            return match (true) {
                isset($record->title) && isset($record->code) => $record->code.' — '.$record->title,
                isset($record->title) => $record->title,
                isset($record->name) => $record->name,
                isset($record->certificate_number) => $record->certificate_number,
                default => '#'.$record->getKey(),
            };
        }

        $props = $activity->properties ?? [];
        $attrs = $props['attributes'] ?? $props['old'] ?? [];

        if (! empty($attrs['title'])) {
            return isset($attrs['code'])
                ? $attrs['code'].' — '.$attrs['title']
                : $attrs['title'];
        }
        if (! empty($attrs['name'])) {
            return $attrs['name'];
        }
        if (! empty($attrs['certificate_number'])) {
            return $attrs['certificate_number'];
        }
        if (! empty($attrs['student_name'])) {
            return $attrs['student_name'];
        }

        return null;
    }
}

if (! function_exists('activity_subject_link')) {
    /**
     * Admin URL to view the underlying record, when it still exists.
     * Returns null for deleted subjects.
     */
    function activity_subject_link(?Activity $activity): ?string
    {
        if (! $activity || ! $activity->subject) {
            return null;
        }

        $type = class_basename($activity->subject_type);
        $id = $activity->subject_id;

        $map = [
            'Course' => 'admin.courses.show',
            'TrainingSchedule' => 'admin.schedules.show',
            'Enrollment' => 'admin.enrollments.show',
            'Certificate' => 'admin.certificates.show',
            'NewsPost' => 'admin.news.edit',
            'Testimonial' => 'admin.testimonials.edit',
            'Facility' => 'admin.facilities.edit',
            'TeamMember' => 'admin.team.edit',
            'CoreValue' => 'admin.core-values.edit',
            'Category' => 'admin.categories.edit',
        ];

        if (! isset($map[$type])) {
            return null;
        }

        $name = $map[$type];
        if (! Route::has($name)) {
            return null;
        }

        return route($name, $id);
    }
}

if (! function_exists('activity_action_sentence')) {
    /**
     * One-line sentence describing what happened, e.g.
     *   "Super Admin created the course RTCR — Radio Telephone Communication and Radar."
     *   "Capt. Juan Dela Cruz updated the enrollment of Maria Santos."
     *   "System deleted the news article 'Old announcement'."
     */
    function activity_action_sentence(?Activity $activity): string
    {
        if (! $activity) {
            return '';
        }

        $causer = activity_causer_name($activity);
        $verb = activity_event_label($activity->event);
        $type = $activity->log_name ? activity_log_type($activity->log_name) : 'record';

        $label = activity_subject_label($activity);
        $deleted = ! $activity->subject && $activity->event === 'deleted';

        if ($label) {
            $article = activity_article_for($type);
            $tail = $deleted ? "(deleted) {$article} {$type} \"{$label}\"" : "{$article} {$type} \"{$label}\"";

            return "{$causer} {$verb} {$tail}.";
        }

        return "{$causer} {$verb} {$article_or_a($type)} {$type}.";
    }
}

if (! function_exists('activity_article_for')) {
    function activity_article_for(string $word): string
    {
        return preg_match('/^[aeiou]/i', $word) ? 'an' : 'a';
    }
}

if (! function_exists('article_or_a')) {
    function article_or_a(string $word): string
    {
        return activity_article_for($word);
    }
}

if (! function_exists('activity_event_badge_class')) {
    /**
     * Tailwind class for the event badge background.
     */
    function activity_event_badge_class(?string $event): string
    {
        return match ($event) {
            'created' => 'bg-green-100 text-green-800',
            'updated' => 'bg-yellow-100 text-yellow-800',
            'deleted' => 'bg-red-100 text-red-800',
            'restored' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}

if (! function_exists('nav_link_class')) {
    /**
     * Returns the CSS class string for an admin-panel navigation link.
     *
     * The link is "active" when the current request matches any of the
     * given route patterns. Patterns may include wildcards (e.g.
     * "admin.courses.*") so a parent section link can light up while
     * the user is on a child page (show, create, edit, etc.).
     *
     * @param  string|array<string>  $patterns
     */
    function nav_link_class(string|array $patterns): string
    {
        $patterns = (array) $patterns;
        $isActive = collect($patterns)->contains(
            fn ($p) => request()->routeIs($p)
        );

        return $isActive
            ? 'bg-[#004080] text-white font-semibold border-l-4 border-[#D4A017]'
            : 'text-blue-200 border-l-4 border-transparent hover:bg-[#004080]/60 hover:text-white';
    }
}

if (! function_exists('public_nav_link_class')) {
    /**
     * Returns the CSS class string for a public-site navigation link.
     * Active links get the brand gold underline; inactive links are
     * plain gray that turn navy on hover.
     *
     * @param  string|array<string>  $patterns
     */
    function public_nav_link_class(string|array $patterns): string
    {
        $patterns = (array) $patterns;
        $isActive = collect($patterns)->contains(
            fn ($p) => request()->routeIs($p)
        );

        return $isActive
            ? 'text-[#003366] font-semibold border-[#D4A017]'
            : 'text-gray-700 border-transparent hover:text-[#003366]';
    }
}
