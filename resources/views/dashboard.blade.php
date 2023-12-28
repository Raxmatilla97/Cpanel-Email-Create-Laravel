<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('OTM nomidan email pochta yaratish sahifasi') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen" style="background-image: url('{{asset('assets/bg.jpg')}}')">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                @if(auth()->user()->emails)
                
                <div class="container mx-auto px-4 sm:px-8">
                    <div class="bg-teal-100 border-t-4 mt-6 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                          <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                          <div>
                            <p class="font-bold">Siz o'z akkauntizdan OTM email pochtasini yaratgansiz!</p>
                            <p class="text-sm">Siz bir akkauntdan bir email ochishingiz mumkin. Boshqa email ochishga ruxsat berilmaydi va buni boshqa odamlar yordamida har-xil maqsadlar uchun qayta ochish yoki bir odam tomonidan boshqalarga ochib berish mumkin emas!</p>
                            <p class="text-sm font-bold text-red-600" >Bunday hollar aniqlansa tegishli tartibda chora ko'riladi!</p>
                            <p class="text-sm font-bold text-blue-600" >Agarda email pochta yaratishda biron muammoga  duch kelsangiz Adminga yozishingiz mumkin! <a class="font-xl text-md text-indigo-700" href="https://t.me/Raxmatilla_Fayziyev" target="_blank" rel="noopener noreferrer">Fayziyev Raxmatilla</a></p>
                          </div>
                        </div>
                      </div>
                      <div class="flex justify-center">
                        <a href="https://email.cspu.uz" target="_blank" rel="noopener noreferrer">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mt-4">
                            Email pochtaga kirish.
                        </button>
                    </a>
                    </div>
                    
                    <div class="py-8">
                        <div class="shadow overflow-hidden rounded border-b border-gray-200">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Holati</th>
                                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Telefon</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    <tr>
                                        <td class="text-left py-3 px-4">Email yaratilgan!</td>
                                        <td class="text-left py-3 px-4">{{ auth()->user()->emails->email }}</td>
                                        <td class="text-left py-3 px-4">{{ auth()->user()->emails->phone }}</td>
                                    </tr>
                                    <!-- Boshqa qatorlarni qo'shish uchun shu formatdan foydalaning -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                @else
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-center max-h-xl mt-12 mb-12">

                        <div class="bg-white p-6 rounded border border-md shadow-lg w-full max-w-xl">
                            @if (session('error'))
                            <div class="bg-red-500 text-white font-bold px-4 py-3 rounded relative" role="alert">
                                {{ session('error') }}
                            </div>
                            @endif

                            @if (session('success'))
                            <div class="bg-green-500 text-white font-bold px-4 py-3 rounded relative" role="alert">
                                {{ session('success') }}
                            </div>
                            @endif

                            <form action="{{ route('email-send')}}" method="post" class="space-y-4">
                                @csrf
                                @method('post')
                                <div class="mx-4">
                                    <label class="block text-md font-medium text-gray-700">Pochtada ko'rinadigan
                                        F.I.SH</label>
                                    <input type="text" name="full_name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                        disabled placeholder="{{ auth()->user()->name }}" required>
                                    <p class="text-sm font-small">Agarda F.I.SH da xatolik bo'lsa uni profildan
                                        to'g'irlashingiz mumkin.</p>
                                </div>
                                <div class="mx-4">
                                    <!-- Telefon Raqami Input -->
                                    <label class="block text-md font-medium text-gray-700">Telefon Raqamingiz</label>
                                    <input type="text" name="phone" value="{{ old('phone')}}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                        required>
                                    @error('phone')
                                    <p class="text-red-500 text-xs font-bold  font-bold  italic">{{ $message }}</p>
                                    @enderror
                                    <p class="text-sm font-small">Agarda mavjud bo'lmagan raqam yozsangiz emailingiz
                                        bloklanadi!</p>
                                </div>

                                <div class="mx-4">
                                    <!-- Parol Input -->
                                    <label class="block text-md font-medium text-gray-700">Emailingiz paroli</label>
                                    <input type="password" name="password" value="{{ old('password')}}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                        required>
                                    @error('password')
                                    <p class="text-red-500 text-xs font-bold italic">{{ $message }}</p>
                                    @enderror
                                    <p class="text-sm font-small">OTM emailiga kirish parolini yozing.</p>
                                </div>

                                <div class="mx-4">
                                    <!-- About Input -->
                                    <label class="block text-md font-medium text-gray-700">O'zingiz haqizda
                                        yozing</label>
                                    <textarea name="about"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                        required>{{ old('about')}}</textarea>
                                    @error('about')
                                    <p class="text-red-500 text-xs font-bold italic">{{ $message }}</p>
                                    @enderror
                                    <p class="text-sm font-small">Fakultet, Kafedra va email pochta nima uchun kerakligi
                                    </p>
                                </div>
                                <div class="text-right">
                                    <input type="submit" value="Yuborish"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>