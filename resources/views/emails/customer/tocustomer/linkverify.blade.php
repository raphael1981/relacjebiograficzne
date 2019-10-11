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
                                                    <h1 class="text-center">Dziękujemy za rejestrację!<br> Aby potwierdzić założenie konta kliknij w poniższy link:</h1>
                                                    <table class="spacer">
                                                        <tbody>
                                                        <tr>
                                                            <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    {{--<p class="text-center">It happens. Click the link below to reset your password.</p>--}}
                                                    <table class="" style="width: 600px">
                                                        <tr>
                                                            <td>
                                                                <a href="{{ url('customer/verify/'.$customer->verification_token) }}" align="center" class="float-center">
                                                                    Kliknij aby zweryfikować rejestrację.
                                                                </a>
                                                            </td>
                                                            <td class="expander"></td>
                                                        </tr>
                                                    </table>
                                                    <hr>
                                                    <p>
                                                        W ciągu dwóch dni roboczych od potwierdzenia przesłania formularza otrzymasz odpowiedź na zgłoszenie rejestracji.<br>
                                                        Jeśli nie zakładałeś konta - Twój adres mógł zostać podany przez inną osobę.<br>
                                                        Jeśli nie chcesz zakładać konta - usuń tę wiadomość bez klikania w powyższy link.
                                                    </p>
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