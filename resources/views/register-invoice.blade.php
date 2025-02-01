<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8"/>
    <title>
    </title>
    <style>
        body {
            line-height: 108%;
            font-family: Calibri;
            font-size: 11pt
        }

        p {
            margin: 0pt 0pt 8pt
        }

        table {
            margin-top: 0pt;
            margin-bottom: 8pt
        }

        .BalloonText {
            margin-bottom: 0pt;
            line-height: normal;
            font-family: Tahoma;
            font-size: 8pt
        }

        .Footer {
            margin-bottom: 0pt;
            line-height: normal;
            font-size: 11pt
        }

        .Header {
            margin-bottom: 0pt;
            line-height: normal;
            font-size: 11pt
        }

        .NoSpacing {
            margin-bottom: 0pt;
            line-height: normal;
            font-size: 11pt
        }

        span.BalloonTextChar {
            font-family: Tahoma;
            font-size: 8pt
        }

        @media (max-width: 900px) {
            img {
                max-width: 100%;
                height: auto;
            }

            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: auto;
            }

            td, th {
                padding: 8px;
                text-align: left;
                border: 1px solid #000000;
            }

            @media print {
                @page {
                    margin: 0;
                }
            }

        }


    </style>
</head>
<body>
<p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:20pt">
    <strong><span style="font-family:'Times New Roman'; ">Kontratë</span></strong><a id="_Hlk72316784"><strong><span
                style="font-family:'Times New Roman'; ">&#xa0;</span></strong><strong><span
                style="font-family:'Times New Roman'; ">për dhënje me qira</span></strong></a>
</p>
<p style="margin-bottom:0pt; text-align:center; line-height:12pt">
    <strong><span
            style="font-family:'Times New Roman'; font-size:10pt; ">{{ $company_name ?? '' }}</span></strong><strong><span
            style="font-family:'Times New Roman'; font-size:10pt; color:#222222">(812112150) {{ $company_address ?? ''}}</span></strong><strong><span
            style="font-family:'Times New Roman'; font-size:10pt; color:#222222">&#xa0; </span></strong><strong><span
            style="font-family:'Times New Roman'; font-size:10pt; color:#222222">Tel/ {{ $company_phone ?? ''}}</span></strong>
</p>
<p style="margin-bottom:0pt; text-align:center; line-height:12pt">
    <strong><span style="font-family:'Times New Roman'; font-size:10pt; color:#222222">Kontratë për automjet ndërmjet subjektit {{ $company_name ?? '' }}</span></strong><strong><span
            style="font-family:'Times New Roman'; font-size:10pt; "> SH.P.K.</span></strong><strong><span
            style="font-family:'Times New Roman'; font-size:10pt; color:#222222"> dhe klientit.</span></strong>
</p>
<p style="margin-bottom:0pt; text-align:center; line-height:12pt">
    <strong><span style="font-family:'Times New Roman'; font-size:10pt; color:#222222">Rent a car contract between the owner of {{ $company_name ?? '' }}</span></strong><strong><span
            style="font-family:'Times New Roman'; font-size:10pt; "> SH.P.K.</span></strong><strong><span
            style="font-family:'Times New Roman'; font-size:10pt; color:#222222"> and the client.</span></strong>
</p>
<p style="margin-bottom:0pt; text-align:center; line-height:12pt">
    <strong><span style="font-family:'Times New Roman'; font-size:10pt; color:#222222">&#xa0;</span></strong>
</p>
<p style="text-align:center; line-height:12pt">
    <strong><span
            style="font-family:'Times New Roman'; font-size:12pt; color:#222222">Klienti/ </span></strong><span
        style="font-family:'Times New Roman'; font-size:12pt; color:#222222">Customer</span><strong><span
            style="font-family:'Times New Roman'; font-size:12pt; color:#222222">:</span></strong>
</p>


<div class="table">


    <table
        style="width:100%; border:0.75pt solid #000000; border-collapse:collapse; margin:auto">
        <tr style="height:16.85pt">
            <td style="width:100%; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Emri</span></strong><span
                        style="font-family:'Times New Roman'">/ Name / </span><strong><span
                            style="font-family:'Times New Roman'; ">Mbiemri/ </span></strong><span
                        style="font-family:'Times New Roman'">Surname:</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span
                        style="font-family:'Times New Roman'">{{$full_name ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:12.3pt">
            <td style="width:100%; border-top:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Numri personal/ </span></strong><span
                        style="font-family:'Times New Roman'">ID card number</span><strong><span
                            style="font-family:'Times New Roman'; ">:</span></strong><strong><span
                            style="font-family:'Times New Roman'; ">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span></strong><span
                        style="font-family:'Times New Roman'">{{$id_card ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:12.3pt">
            <td style="width:100%; border-top:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Adresa</span></strong><span
                        style="font-family:'Times New Roman'">/ Adress:</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span
                        style="font-family:'Times New Roman'">{{$address ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:13.7pt">
            <td style="width:100%; border-top:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:12pt">
                    <strong><span
                            style="font-family:'Times New Roman'; ">Data e lindjes dhe vendlindja/</span></strong><span
                        style="font-family:'Times New Roman'"> Date of birth and birthplace:</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0; </span><span
                        style="font-family:'Times New Roman'">{{$birth ?? ''}} {{$birthplace ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:15pt">
            <td style="width:100%; border-top:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:12pt">
                    <strong><span
                            style="font-family:'Times New Roman'; ">Numri i patent shoferit/ </span></strong><span
                        style="font-family:'Times New Roman'">Driver’s license number:</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span
                        style="font-family:'Times New Roman'">{{$drivers_license_id ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:12.15pt">
            <td style="width:100%; border-top:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Telefoni/ </span></strong><span
                        style="font-family:'Times New Roman'">Phone number:</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span
                        style="font-family:'Times New Roman'">{{$phone ?? ''}}</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span>
                </p>
            </td>
        </tr>
    </table>
    <p style="text-align:center; line-height:normal; font-size:12pt; margin-top: 1rem">
        <strong><span style="font-family:'Times New Roman'; ">Mjeti i huazuar/ </span></strong><span
            style="font-family:'Times New Roman'">Rented vehicle</span>
    </p>
    <table
        style="width:100%; border:0.75pt solid #000000; border-collapse:collapse; margin:auto; ">
        <tr style="height:15pt">
            <td style="width:50%; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal">
                    <strong><span style="font-family:'Times New Roman'; ">Lloji i automjetit/ </span></strong><span
                        style="font-family:'Times New Roman'">Type</span><strong><span
                            style="font-family:'Times New Roman'; ">:</span></strong><strong><span
                            style="font-family:'Times New Roman'; ">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span></strong><span
                        style="font-family:'Times New Roman'">{{$model ?? ''}}</span>
                </p>
            </td>
            <td style="width:251.45pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal">
                    <strong><span style="font-family:'Times New Roman'; ">Ngjyra/ </span></strong><span
                        style="font-family:'Times New Roman'">Color</span><strong><span
                            style="font-family:'Times New Roman'; ">:</span></strong><strong><span
                            style="font-family:'Times New Roman'; ">&#xa0;&#xa0;&#xa0;&#xa0; </span></strong><span
                        style="font-family:'Times New Roman'">{{$color ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:15pt">
            <td style="width:50%; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal">
                    <strong><span style="font-family:'Times New Roman'; ">Nr.Shasisë/ </span></strong><span
                        style="font-family:'Times New Roman'">Chassis</span><strong><span
                            style="font-family:'Times New Roman'; ">:</span></strong><strong><span
                            style="font-family:'Times New Roman'; ">&#xa0;&#xa0;&#xa0;&#xa0; </span></strong><span
                        style="font-family:'Times New Roman'">{{$shasi_nr ?? ''}}</span>
                </p>
            </td>
            <td style="width:251.45pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal">
                    <strong><span style="font-family:'Times New Roman'; ">Viti prodhimit/ </span></strong><span
                        style="font-family:'Times New Roman'">production year</span><strong><span
                            style="font-family:'Times New Roman'; ">:</span></strong><strong><span
                            style="font-family:'Times New Roman'; ">&#xa0;&#xa0; </span></strong><span
                        style="font-family:'Times New Roman'">{{$production_year ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:15pt">
            <td style="width:50%; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal">
                    <strong><span style="font-family:'Times New Roman'; ">Targat/ </span></strong><span
                        style="font-family:'Times New Roman'">Vehicle number:</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span
                        style="font-family:'Times New Roman'">{{$target ?? ''}}</span>
                </p>
            </td>
            <td style="width:251.45pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal">
                    <strong><span style="font-family:'Times New Roman'; ">Karburanti/ </span></strong><span
                        style="font-family:'Times New Roman'">Fuel</span><strong><span
                            style="font-family:'Times New Roman'; ">: </span></strong><span
                        style="font-family:'Times New Roman'">&#xa0;</span><span
                        style="font-family:'Times New Roman'">{{$fuel_status ?? ''}}</span>
                </p>
            </td>
        </tr>
    </table>
    <p style="text-align:center; line-height:normal; font-size:12pt; margin-top: 1rem">
        <strong><span style="font-family:'Times New Roman'; ">Detajet e pagesës/ </span></strong><span
            style="font-family:'Times New Roman'">Payment details</span>
    </p>
    <table
        style="width:100%; border:0.75pt solid #000000; border-collapse:collapse; margin:auto">
        <tr style="height:15pt">
            <td style="width:236.3pt; border-right:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Dita e marrjes/ </span></strong><span
                        style="font-family:'Times New Roman'">Start date:</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0; </span><span
                        style="font-family:'Times New Roman'; font-size:11pt">{{$start_date ?? ''}}</span>
                </p>
            </td>
            <td style="width:236.3pt; border-left:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Dita e kthimit/ </span></strong><span
                        style="font-family:'Times New Roman'">Return date:</span><span
                        style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span
                        style="font-family:'Times New Roman'; font-size:11pt">{{$end_date ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:15pt">
            <td colspan="2"
                style="width:483.4pt; border-top:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Çmimi për 1 ditë/ </span></strong><span
                        style="font-family:'Times New Roman'">Price per Day</span><strong><span
                            style="font-family:'Times New Roman'; ">:</span></strong><strong><span
                            style="font-family:'Times New Roman'; ">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span></strong><span
                        style="font-family:'Times New Roman'; font-size:11pt">{{$price_per_day ?? ''}}</span>
                </p>
            </td>
        </tr>
        <tr style="height:15pt">
            <td style="width:236.3pt; border-top:0.75pt solid #000000; border-right:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Ditët/ </span></strong><span
                        style="font-family:'Times New Roman'">Days</span><strong><span
                            style="font-family:'Times New Roman'; ">:</span></strong><strong><span
                            style="font-family:'Times New Roman'; ">&#xa0;&#xa0; </span></strong><span
                        style="font-family:'Times New Roman'">{{$days ?? ''}}</span>
                </p>
            </td>
            <td style="width:236.3pt; border-top:0.75pt solid #000000; border-left:0.75pt solid #000000; padding-right:5.03pt; padding-left:5.03pt; vertical-align:top">
                <p style="margin-bottom:0pt; line-height:normal; font-size:12pt">
                    <strong><span style="font-family:'Times New Roman'; ">Vlera Totale/ </span></strong><span
                        style="font-family:'Times New Roman'">Total Value</span><strong><span
                            style="font-family:'Times New Roman'; ">:</span></strong><strong><span
                            style="font-family:'Times New Roman'; ">&#xa0; </span></strong><span
                        style="font-family:'Times New Roman'">{{$total_price ?? ''}}</span>
                </p>
            </td>
        </tr>
    </table>
    <p style="text-align:justify">
        &#xa0;
    </p>
    <!-- <p style="margin-bottom:0pt; line-height:normal; font-size:10pt">
        <strong><span style="font-family:'Times New Roman'; ">Gjendja e mjetit në shkuarje:</span></strong><strong><span style="font-family:'Times New Roman'; ">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span></strong><strong><span style="font-family:'Times New Roman'; ">Gjendja e mjetit në kthim:</span></strong>
    </p>
    <p style="margin-bottom:0pt; line-height:normal; font-size:10pt">
        <span style="font-family:'Times New Roman'">Vehicle status in Departure</span><span style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span style="font-family:'Times New Roman'">Vehicle status in Return</span>
    </p>
    <p style="line-height:108%; font-size:10pt">
        <span style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span>
    </p>
    <p style="margin-bottom:0pt; line-height:108%; font-size:10pt">
        <img src="1731322359_kontrata/1731322359_kontrata-1.jpeg" width="122" height="55" alt="C:\Users\IT-Solution\Desktop\empty-fuel-gauge-meter_97886-989.jpg" /><span style="font-family:'Times New Roman'">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><img src="1731322359_kontrata/1731322359_kontrata-2.jpeg" width="95" height="58" alt="C:\Users\IT-Solution\Desktop\empty-fuel-gauge-meter_97886-989.jpg" />
    </p> -->
</div>


<p style="line-height:normal; font-size:9pt">
    <strong><span style="font-family:'Times New Roman'; ">Vërejtje/ </span></strong><span
        style="font-family:'Times New Roman'">Warning</span><strong><span
            style="font-family:'Times New Roman'; "> </span></strong>
</p>
<p class="NoSpacing" style="font-size:9pt">
    <strong><span style="font-family:'Times New Roman'; ">Sigurimi: </span></strong><span
        style="font-family:'Times New Roman'">Çdo dëmtim eventual i mjetit që rezulton i qëllimshëm, do të kompensohet nga klienti dhe çdo shkelje ligjore e mundshme, përgjegjsinë e merr vetë klienti. Kompania e Sigurimit i përgjigjet vozitesit në rastin kur raporti gjenerohet nga Policia rrugore kurse klienti është i detyruar të paguaj 20% te dëmit dhe ditet që mjeti është në garazhë duke u riparuar. </span>
</p>
<p class="NoSpacing" style="font-size:9pt">
    <strong><span style="font-family:'Times New Roman'; ">Insurance: </span></strong><span
        style="font-family:'Times New Roman'">Any eventual damage to the vehicle that turns out to be intentional will be compensated by the client and any possible legal violations, the client takes responsibility. The Insurance Company responds to the driver in case the report is generated by the Traffic Police and the client is obliged to pay 20% of th damage and the days when the car is in the Garage being repaired. </span>
</p>
<p class="NoSpacing" style="font-size:9pt">
    <span style="font-family:'Times New Roman'">&#xa0;</span>
</p>
<p class="NoSpacing" style="font-size:9pt">
    <strong><span style="font-family:'Times New Roman'; ">Me këtë autorizim</span></strong><span
        style="font-family:'Times New Roman'">: I lejohet klientit të ngasë makinën brenda dhe jashte shtetit në bazë të kushteve të caktuar, klienti nuk ka të drejtë mjetin e marre me qera ta jep nën qeramarrje apo shitje e as ti lejohet personit tjeter ta ngase mjetin perveq personit qe i është lëshuar kontrata nga {{ $company_name ?? '' }} dhe çdo shkelje ligjore e mundshme, pergjegjsine e merr vetë klienti.</span>
</p>
<p class="NoSpacing" style="font-size:9pt">
    <strong><span style="font-family:'Times New Roman'; ">With this authorization</span></strong><span
        style="font-family:'Times New Roman'"> the client is allowed to drive the car inside and ouotside country based on certain conditions, the client has no right to rent or sell the rented vehicle nor to allow the other person to drive the car except the person to whom the contract has been issued by {{ $company_name ?? '' }}</span><span
        style="font-family:'Times New Roman'">&#xa0; </span><span style="font-family:'Times New Roman'">and any possible legal violations, the client takes responsibility himself. </span>
</p>
<p class="NoSpacing" style="font-size:9pt">
    <span style="font-family:'Times New Roman'">&#xa0;</span>
</p>
<p class="NoSpacing" style="font-size:9pt">
    <strong><span style="font-family:'Times New Roman'; ">Mjeti </span></strong><span
        style="font-family:'Times New Roman'">duhet te kthehet sipas marrveshjes gjate marrjes se mjetit. Nese klienti nuk i pershtatet kushte vete dhenjes me qira dhe mjeti vonohet ateher klientit i vazhdon kontrata per çdo vones me pagese. Mjeti duhet të jetë në gjendje të rregullt mekanike, teknike dhe vizuale, e pastruar dhe të ketë të njejtën sasi karburanti si ne daten e marrjes. Ne rast të ndonjë defekti të mjeti klienti është i obliguar të njoftojë me kohë e nese rezultonë që mjeti ka defekte apo është vozitur dhunshëm përgjegjsinë e merr vetë klienti. </span>
</p>
<p class="NoSpacing" style="font-size:8pt">
    <span style="font-family:'Times New Roman'">&#xa0;</span>
</p>
<p class="NoSpacing" style="line-height:200%">
    <span style="font-family:'Times New Roman'">Nënshkrimi i Kompanisë:</span><span
        style="font-family:'Times New Roman'">&#xa0;&#xa0; </span><span
        style="line-height:200%; font-family:'Times New Roman'; font-size:8pt">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span
        style="font-family:'Times New Roman'">Nënshkrimi i klientit:</span>
</p>
<p class="NoSpacing" style="line-height:200%">
        <span
            style="line-height:200%; font-family:'Times New Roman'; font-size:8pt">__________________________</span><span
        style="width:4pt; display:inline-block">&#xa0;</span><span
        style="width:36pt; display:inline-block">&#xa0;</span><span
        style="width:36pt; display:inline-block">&#xa0;</span><span
        style="width:36pt; display:inline-block">&#xa0;</span><span
        style="width:36pt; display:inline-block">&#xa0;</span><span
        style="line-height:200%; font-family:'Times New Roman'; font-size:8pt">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><span
        style="line-height:200%; font-family:'Times New Roman'; font-size:8pt">___________________________ </span>
</p>
</body>
</html>
