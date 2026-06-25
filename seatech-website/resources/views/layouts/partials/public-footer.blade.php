<footer class="bg-[#003366] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ asset('images/logo.webp') }}" alt="SEATECH Legazpi" class="h-12 w-12 rounded-full object-cover bg-white p-1">
                    <div>
                        <span class="text-white font-bold text-lg leading-tight block">SEATECH Legazpi</span>
                        <span class="text-blue-200 text-xs leading-tight block">Maritime Training Center</span>
                    </div>
                </div>
                <p class="text-blue-200 text-sm leading-relaxed">
                    Home of Maritime Training and Assessment for Seafarers at the Capital City of Bicol.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-4 text-[#D4A017]">Quick Links</h3>
                <ul class="space-y-2 text-sm text-blue-200">
                    <li><a href="{{ route('courses') }}" class="hover:text-white transition">Training Programs</a></li>
                    <li><a href="{{ route('calendar') }}" class="hover:text-white transition">Schedule</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                    <li><a href="{{ route('verify.certificate') }}" class="hover:text-white transition">Verify Certificate</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-4 text-[#D4A017]">Contact Info</h3>
                <ul class="space-y-2 text-sm text-blue-200">
                    <li>Legazpi City, Albay, Philippines</li>
                    <li>+63 (XXX) XXX-XXXX</li>
                    <li>info@seatechmaritime.com</li>
                    <li class="pt-2">
                        <span class="text-white font-medium">Office Hours:</span><br>
                        Mon - Fri: 8:00 AM - 5:00 PM
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-blue-800 mt-8 pt-8 text-center text-sm text-blue-300">
            &copy; {{ date('Y') }} SEATECH Maritime Training & Assessment Center, Inc. All rights reserved.
        </div>
    </div>
</footer>
