<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Title</title>
</head>

<body>
<!-- <style> -->
<table class="body" data-made-with-foundation="">
    <tr>
        <td class="float-center" align="center" valign="top">
            <center data-parsed="">
                <table class="spacer float-center">
                    <tbody>
                    <tr>
                        <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                    </tr>
                    </tbody>
                </table>
                <table align="center" class="container float-center">
                    <tbody>
                    <tr>
                        <td>
                            <table class="row header">
                                <tbody>
                                <tr>
                                    <th class="small-12 large-12 columns first last">
                                        <table>
                                            <tr>
                                                <th>
                                                    <table class="spacer">
                                                        <tbody>
                                                        <tr>
                                                            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <h4 class="text-center" style="text-align: center">

                                                    </h4>
                                                </th>
                                                <th class="expander"></th>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                            <table class="row">
                                <tbody>
                                <tr>
                                    <th class="small-12 large-12 columns first last">
                                        <table>
                                            <tr>
                                                <th>
                                                    <table class="spacer">
                                                        <tbody>
                                                        <tr>
                                                            <td height="32px" style="font-size:32px;line-height:32px;">&#xA0;</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <center data-parsed=""> <img src="{{ asset('images/logo_ahm.png') }}" align="center" class="float-center"> </center>
                                                    <table class="spacer">
                                                        <tbody>
                                                        <tr>
                                                            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <h1 class="text-center">Użytkownik zweryfikował swoją rejestację na portalu
                                                        <a href="http://relacjebiograficzne.pl/" target="_blank">www.relacjebiograficzne.pl</a>.</h1>
                                                    <p style="text-align: center">
                                                        Kliknij link aby udostępnić użykownikowi pełen dostęp do serwisu.
                                                    </p>

                                                    <p style="text-align: center">
                                                        <h3>
                                                            <a href="{{ $adminprotocol.'://'.$admindomain.'/customer/link/akcept/'.$customer->id.'/'.$token }}">
                                                                Link werefikacyjny
                                                            </a>
                                                        </h3>
                                                    </p>

                                                    <table class="spacer">
                                                        <tbody>
                                                        <tr>
                                                            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <table class="" style="width: 100%">
                                                        <tr>
                                                            <td>

                                                                <h2>
                                                                    Dane użytkownika
                                                                </h2>

                                                                <tabel style="width: 100%">
                                                                    <tr>

                                                                        <td style="text-align: right">
                                                                            Imię i Nazwisko:
                                                                        </td>

                                                                        <td>
                                                                            &nbsp;&nbsp;
                                                                        </td>

                                                                        <td>
                                                                            {{ $customer->name }} {{ $customer->surname }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align: right">
                                                                            Email:
                                                                        </td>

                                                                        <td>
                                                                            &nbsp;&nbsp;
                                                                        </td>

                                                                        <td>
                                                                            {{ $customer->email }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align: right">
                                                                            Telefon:
                                                                        </td>

                                                                        <td>
                                                                            &nbsp;&nbsp;
                                                                        </td>

                                                                        <td>
                                                                            {{ $customer->phone }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align: right">
                                                                            Rejestacja dla:
                                                                        </td>

                                                                        <td>
                                                                            &nbsp;&nbsp;
                                                                        </td>

                                                                        <td>
                                                                            {{ $customer->customer_type }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align: right">
                                                                            W celu:
                                                                        </td>

                                                                        <td>
                                                                            &nbsp;&nbsp;
                                                                        </td>

                                                                        <td>
                                                                            {{ $customer->register_target }}
                                                                        </td>

                                                                    </tr>
                                                                </tabel>

                                                            </td>
                                                            <td class="expander"></td>
                                                        </tr>
                                                    </table>
                                                    <hr>
                                                    {{--<p>You're getting this email because you've signed up for email updates. If you want to opt-out of future emails,</p>--}}
                                                </th>
                                                <th class="expander"></th>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>
                                </tbody>
                            </table>
                            <table class="spacer">
                                <tbody>
                                <tr>
                                    <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </center>
        </td>
    </tr>
</table>
</body>
</html>