<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;
use App\Models\User; // Foydalanuvchi modeli
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validatsiya
        $validatedData = $request->validate([            
            'phone' => 'required|min:8|max:20',
            'password' => 'required|min:8|regex:/[A-Za-z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'about' => 'required|min:10|max:1000',
        ], [
            'phone.required' => 'Telefon raqami talab qilinadi.',
            'phone.min' => 'Telefon raqami kamida 8 raqamdan iborat bo\'lishi kerak.',
            'phone.max' => 'Telefon raqami 20 raqamdan oshmasligi kerak.',
            'password.required' => 'Parol talab qilinadi.',
            'password.min' => 'Parol kamida 8 belgidan iborat bo\'lishi kerak.',
            'password.regex' => 'Parol harflar, raqamlar va maxsus belgilarni o\'z ichiga olishi kerak.',
            'about.required' => 'Ma\'lumotlar maydoni talab qilinadi.',
            'about.min' => 'Ma\'lumotlar kamida 10 belgidan iborat bo\'lishi kerak.',
            'about.max' => 'Ma\'lumotlar 1000 belgidan oshmasligi kerak.',
        ]);

        // Foydalanuvchi nomini olish
        $user_name = Auth::user()->name;
  
        // Email hosil qilish
        $emailParts = explode(" ", strtolower($user_name));
        $email_account = $emailParts[0] . '.' . $emailParts[1]; // Email hisobi
        $email = $email_account . '@cspu.uz';

        // cPanel'da Email Hisobini Yaratish
        $cp_user = 'lvlupuz'; // cPanel foydalanuvchi nomi
        $cp_pass = 'Zj,-DGGzs%DP'; // cPanel paroli
        $cp_domain = 'https://server1.ahost.cloud:2083'; // cPanel URLi

        $curl = curl_init();            
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $headers = [
            'Authorization: Basic ' . base64_encode($cp_user . ':' . $cp_pass)
        ];

        $query = http_build_query([
            'cpanel_jsonapi_user' => $cp_user,
            'cpanel_jsonapi_apiversion' => 3,
            'cpanel_jsonapi_module' => 'Email',
            'cpanel_jsonapi_func' => 'add_pop',
            'email' => $email_account,
            'password' => $request->password,
            'domain' => 'cspu.uz',
            'quota' => 100
        ]);

    

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $cp_domain . '/execute/Email/add_pop');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $query);


        $response = curl_exec($curl);

        if (curl_error($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            Log::error("Email yaratishda xatolik: " . $error);
            return back()->with('error', 'Email hisobini yaratishda xatolik yuz berdi.')->withInput();
        } else {
            $responseData = json_decode($response, true);
        
            if (isset($responseData['status']) && $responseData['status'] == 1) {
               // Ma'lumotlarni Ma'lumotlar Bazasiga Saqlash
                $user = new Email();
                $user->full_name = Auth::user()->name;
                $user->email = $email;
                $user->password = bcrypt($request->password);
                $user->phone = $request->phone;
                $user->text_info = $request->about;
                $user->user_id = Auth::user()->id;
                $user->save();
        
                return back()->with('success', 'Foydalanuvchi muvaffaqiyatli qo\'shildi.');
            } elseif (isset($responseData['status']) && $responseData['status'] == 0) {
                $errorMessage = isset($responseData['errors']) ? implode(', ', $responseData['errors']) : 'Noma\'lum xatolik yuz berdi.';
                
                // Xabar matnini tekshirish va tarjima qilish
                if (strpos($errorMessage, 'The password that you entered has a strength rating of “1”.') !== false) {
                    $errorMessage = "API hatosi: Siz yozgan parol xavfsizlik jihatidan zaif hisoblanadi! Uni qayta xavfsizlikk jihatidan kuchliroq qilib yozing. (123456Abc@#$) misolida!";
                }elseif (strpos($errorMessage, 'The account') !== false) {
                    $errorMessage = "API hatosi: Sizning OTM email pochtangiz allaqachon ro'yxatga olingan! (Adminga yozing: https://t.me/Raxmatilla_Fayziyev ) biz buni aniqlashtiramiz :)";
                }
        
                return back()->with('error', $errorMessage)->withInput();
            } else {
                return back()->with('error', 'Noma\'lum xatolik yuz berdi.')->withInput();
            }
        }
        
        curl_close($curl);

       
    }

    /**
     * Display the specified resource.
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Email $email)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email)
    {
        //
    }
}
