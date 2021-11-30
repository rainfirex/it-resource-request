<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF</title>
    <link rel="stylesheet" href="{{ ltrim('/css/render.css', '/') }}"/>
</head>
<body>
<div>
    <!--Печать-->
    <div style="margin: 0 0 4px 0" class="print font-small">
        <p>"РАЗРЕШЕНО"</p>
        <p>Главный инженер Сахалинэнергосбыт</p>
        <p>филиал ПАО ДЕК</p>
        <p>___________/ О. Ю. Панитков</p>
        <p class="text-underline">01 февраля 2021 года</p>
    </div>
    <div class="clear"></div>
    <h2 class="text-center">ЗАЯВКА</h2>
    <h2 class="text-center">на предоставление доступа к ИР</h2>
    <!--Информация о сотруднике-->
    <div style="margin-top: 10px">
        <div class="x-cols">
            <div class="x-col-1">Сотрудник:</div>
            <div class="x-col-2 border-bottom">{{ $user->name }}</div>
        </div>
        <div class="clear"></div>
        <div class="x-cols">
            <div class="x-col-1">Должность:</div>
            <div class="x-col-2 border-bottom">{{ $user->function }}</div>
        </div>
        <div class="clear"></div>
        <div class="x-cols">
            <div class="x-col-1">Подразделение:</div>
            <div class="x-col-2 border-bottom">{{ $user->unit }}</div>
        </div>
        <div class="clear"></div>
        <div class="x-cols">
            <div class="x-col-1">Место работы:</div>
            <div class="x-col-2 border-bottom">{{ $user->address }} Кабинет: {{ $user->cabinet }}</div>
        </div>
        <div class="clear"></div>
        <div class="x-cols">
            <div class="x-col-1">Телефон:</div>
            <div class="x-col-2 border-bottom">{{ $user->phone }}</div>
        </div>
        <div class="clear"></div>
        <div class="x-cols">
            <div class="x-col-1">IP Телефон:</div>
            <div class="x-col-2 border-bottom">{{ $user->ipPhone }}</div>
        </div>
        <div class="clear"></div>
        <div class="x-cols">
            <div class="x-col-1">В период с:</div>
            <div class="x-col-2 border-bottom">{{ $user->perStart }} по: {{ $user->perEnd }}</div>
        </div>
        <div class="clear"></div>
    </div>
    <!--Доступ к информационным ресурсам-->
    <div style="margin-top: 10px">
        <h3 style="margin-bottom: 4px">1. Доступ к информационным ресурсам</h3>
        <div class="ir">
            @if($user->access->isLogin)
                <span>Доступ к ресурсам ЛВС (учетная запись)</span>
            @endif

            @if($user->access->is1CUPP)
                <span> 1С УПП</span>
            @endif

            @if($user->access->is1CZPP)
                <span>1С Зарплата и Управление персоналом</span>
            @endif

            @if($user->access->isAsuse)
                <span>АСУСЭ</span>
            @endif

            @if($user->access->isOmniusFL)
                <span>ОМНИУС ФЛ</span>
            @endif

            @if($user->access->isOmniusYUL)
                <span>ОМНИУС ЮЛ</span>
            @endif

            @if($user->access->isUSB)
                <span>USB</span>
            @endif

            @if($user->access->isEmail)
                <span>Электронная почта</span>
            @endif

            @if($user->access->isInternet)
                <span>Интернет</span>
            @endif

            @if($user->access->isConsult)
                <span>Консультант плюс</span>
            @endif

            @if($user->access->isFolderObmen)
                <span>Папка обмен</span>
            @endif

            @if($user->access->isWorkFromUTD)
                <span>Work от УТД</span>
            @endif
        </div>
    </div>
    <!--Доступ к сетевым ресурсам-->
    <div style="margin-top: 10px">
        <h3 style="margin-bottom: 4px">2. Доступ к сетевым ресурсам</h3>
        @if($user->access->lanResource)
            <p>{{$user->access->lanResource}}</p>
        @endif
    </div>
    <!--Другие программы-->
    <div style="margin-top: 8px">
        <p style="margin-bottom: 4px"><b>Другие программы:</b></p>
        @if($user->access->otherProgram)
            <p>{{ $user->access->otherProgram }}</p>
        @endif
    </div>
    <!--Подписи-->
    <div style="margin-top: 18px">
        @if($user->access->is1CZPP || $user->access->is1CUPP)
            <div class="x-cols">
                <div class="x-col-1">Главный бухгалтер:</div>
                <div class="x-col-2 border-bottom font-small">
                    <div style="margin-left: 64%; position: relative;">
                        <img class="signature">/ Покровская Е. В.
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        @endif

        <div class="x-cols">
            <div class="x-col-1">Руководитель подразделения:</div>
            <div class="x-col-2 border-bottom font-small">
                <div style="margin-left: 64%; position: relative;">
                    <img class="signature">/ {{ $user->shortLeader }}
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="x-cols">
            <div class="x-col-1">Сотрудник:</div>
            <div class="x-col-2 border-bottom font-small">
                <div style="margin-left: 64%; position: relative;">
                    <img class="signature">/ {{ $user->shortName}}
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <!--Согласовано-->
    <div>
        <p style="margin: 10px 0"><b>СОГЛАСОВАНО</b></p>
        <div class="x-cols">
            <div class="x-col-1">Начальник отдела ИТ:</div>
            <div class="x-col-2 border-bottom font-small">
                <div style="margin-left: 64%; position: relative;">
{{--                    data:image/png;base64,{{base64_encode($signatures[1]->picture)}}--}}
                    <img class="signature" src=""
                         alt="pic2">/ Горбенко П. А.
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="x-cols">
            <div class="x-col-1">Администратор ИР:</div>
            <div class="x-col-2 border-bottom font-small">
                <div style="margin-left: 64%; position: relative;">
                    <img class="signature" src="data:image/png;base64,{{base64_encode($signatures[0]->picture)}}"
                         alt="pic2">/ Белоногов А. А.
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <!--Учетная запись-->
    <div style="margin-top: 15px">
        <div class="x-cols">
            <div class="x-col-1">
                Сотруднику присвоена
                учетная запись:
            </div>
            <div class="x-col-2">
                <div>
                    <p class="border-bottom" style="margin-bottom: 5px">Учетная запись: {{ $user->login }}</p>
                    <p class="border-bottom">Электронная почта: {{ $user->email  }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
