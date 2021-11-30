<?php

namespace App\Http\Controllers;

use App\Mail\FeedMail;
use App\Modules\StringGenerator;
use App\ResourceAccess;
use App\Signatures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class CResourceAccess extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('index', 'show');
    }

    public function render(){
        $user = self::fakeUser();
        $user->shortName = StringGenerator::shortUsername($user->name);
        $user->shortLeader = StringGenerator::shortUsername($user->leader);
        $user->login = StringGenerator::login($user->name);
        $user->email = StringGenerator::email($user->name, 'sakh.dvec.ru');

        $signatures = Signatures::all();
        $mpdf = \PDF::loadView('render', compact('user', 'signatures'));
        return $mpdf->stream('contract.pdf');
    }

    public function test(){
        $user = self::fakeUser();
        $user->shortName = StringGenerator::shortUsername($user->name);
        $user->shortLeader = StringGenerator::shortUsername($user->leader);
        $user->login = StringGenerator::login($user->name);
        $user->email = StringGenerator::email($user->name, 'sakh.dvec.ru');

        $signatures = Signatures::all();
        return view('render', compact('user', 'signatures'));
    }

    public function index() {
        $count = ResourceAccess::orderBy('id', 'DESC')->count();
        $resources = ResourceAccess::orderBy('id', 'DESC')->get();
        return response()->json(compact('resources', 'count'));
    }

    public function show(int $id){
        $resource = ResourceAccess::find($id);
        return response()->json(compact('resource'));
    }

    public function store(Request $request)
    {
        $user = json_decode($request->input('user'));

        $user->shortName = StringGenerator::shortUsername($user->name);
        $user->shortLeader = StringGenerator::shortUsername($user->leader);
        $user->login = StringGenerator::login($user->name);;
        $user->email = StringGenerator::email($user->name, 'sakh.dvec.ru');

        // Определение интервала допустимости дат
        {
            $dStart = new \DateTime($user->perStart);
            $dEnd = new \DateTime($user->perEnd);
            $currentStartYear = $dStart->format("Y")+0;
            $currentEndYear = $dEnd->format("Y")+0;

            $minYear = date("Y")-1;
            $limitYear = date("Y")+1;

            if($currentStartYear >= $limitYear || $currentEndYear <= $minYear || $currentStartYear <= $minYear || $currentEndYear >= $limitYear )
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Период заявки не может быть раньше или позже текущего года.'
                ]);
            }
        }

        // Установка PHP параметров
        {
            set_time_limit(1000);
            ini_set('memory_limit', '2024m');
        }

        $signatures = Signatures::all();

        $pdf = PDF::loadView('pdf', [
            'user' => $user,
            'signatures' => $signatures
        ]);

        date_default_timezone_set("Asia/Sakhalin");
        $filename = date('d.m.Y_His') . '_access.pdf';

        $dirStorageApp = storage_path('app/public/');
        $dirAccess = 'access_pdf/';
        $dirAccessToday = $dirStorageApp . $dirAccess . date('d.m.Y');

        if (!file_exists($dirStorageApp . $dirAccess)) {
            mkdir($dirStorageApp . $dirAccess);
            chmod($dirStorageApp . $dirAccess, 0777);
        }

        if (!file_exists($dirAccessToday)) {
            mkdir($dirAccessToday);
            chmod($dirAccessToday, 0777);
        }

        $pdf->save($dirAccessToday . '/' . $filename);

        if($pdf) {
            chmod($dirAccessToday . '/' . $filename, 0777);
            if(ResourceAccess::create([
                'name' => $user->name,
                'leader' => $user->leader,
                'function' => $user->function,
                'unit' => $user->unit,
                'address' => $user->address,
                'cabinet' => $user->cabinet,
                'phone' => $user->phone,
                'ipPhone' => $user->ipPhone,
                'perStart' => $user->perStart,
                'perEnd' => $user->perEnd,
                'isLogin' => $user->access->isLogin,
                'is1CUPP' => $user->access->is1CUPP,
                'is1CZPP' => $user->access->is1CZPP,
                'isAsuse' => $user->access->isAsuse,
                'isOmniusFL' => $user->access->isOmniusFL,
                'isOmniusYUL' => $user->access->isOmniusYUL,
                'isOmnius' => $user->access->isOmnius,
                'isUSB' => $user->access->isUSB,
                'isFolderObmen' => $user->access->isFolderObmen,
                'isWorkFromUTD' => $user->access->isWorkFromUTD,
                'isEmail' => $user->access->isEmail,
                'isInternet' => $user->access->isInternet,
                'isConsult' => $user->access->isConsult,
                'lanResource' => $user->access->lanResource,
                'otherProgram' => $user->access->otherProgram,
                'pdf_path' => $dirAccessToday . '/' . $filename,
                'pdf_url' => asset('storage/' . $dirAccess . date('d.m.Y') . '/' . $filename)
            ])) {
                $files[] = $dirAccessToday . '/' . $filename;
                self::sendMail($user->name, $files);

                return response()->json([
                    'success' => true
                ]);
            } else{
                return response()->json([
                    'success' => false
                ]);
            }
        } else{
            return response()->json([
                'success' => false
            ]);
        }
    }

    /**
     * Фейковый пользователь
     * @return mixed
     */
    private static function fakeUser() {
        return json_decode('{"name":"Файковый Пупкин Пупович",
            "leader":"Файковый Лидер",
            "function":"Фейкер!",
            "unit":"Отдел тестирования",
            "address":"Амурская 879",
            "cabinet":"666",
            "phone":"7299878",
            "ipPhone": "7895",
            "perStart":"12.12.2012",
            "perEnd":"13.12.2012",
            "access":{
                "isOmniusYUL":true, "isOmniusFL": true, "isLogin":true,"is1CUPP":true,"is1CZPP":true,"isAsuse":true,"isUSB":true,"isFolderObmen":true,"isWorkFromUTD":true,"isEmail":true,
                "isInternet":true,"isConsult":true,"lanResource":"NetWork","otherProgram":"Other programm"}}');
    }

    /**
     * Отправка письма
     * @param string $username
     * @param array $files
     */
    private static function sendMail(string $username, array $files){
        $recipients = [
            'Shaposhnikov-SM@sakh.dvec.ru',
            'Poplavskiy-AA@sakh.dvec.ru'
        ];

        foreach ($recipients as $recipient){
            Mail::to($recipient)->send(new FeedMail('mail', 'Новая заявка на И.Р.', [
                'title' => 'Новая заявка на И.Р.',
                'body' => 'Сотрудник: '.$username,
                'file' => $files
            ]));
        }
    }
}
