<?php
/*
* CguruAbsensi
* CetakViewRekapAbsenFinger
* view-rekap-absen-sekolah-guru
* cetak rekap absen sekolah PDF
*/

$ttd = $_GET['ttd'];
?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <style type="text/css">
        @media print {
            table {
                page-break-after: auto;
            }

            tr {
                page-break-inside: avoid;
            }

            td {
                page-break-inside: auto;
            }

            thead {
                display: table-header-group
            }

            .row-fluid [class*="span"] {
                min-height: 20px;
            }
        }

        @page {
            margin-top: 1cm;
            margin-right: 1cm;
            margin-bottom: 2cm;
            margin-left: 2cm;
            size: portrait;
            /*
                size:landscape;
                -webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);
                filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
                */

        }

        ;
    </style>
    {{-- tambahan mryes --}}
    <style type="text/css">
        @font-face {
            font-family: MyBarcode;
            src: url(barcode.woff)
        }

        * {
            margin: 0;
            padding: 0;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box
        }

        .page {
            position: relative;
            width: 21cm;
            min-height: 29cm;
            page-break-after: always;
            margin: 0.5cm auto;
            background: #FFF;
            padding: 1.5cm;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            -webkit-box-sizing: initial;
            -moz-box-sizing: initial;
            box-sizing: initial;
            page-break-after: always
        }

        .page * {
            font-family: arial;
            font-size: 11px
        }

        .page-landscape {
            position: relative;
            width: 29.7cm;
            min-height: 21cm;
            page-break-after: always;
            margin: 0.5cm;
            background: #FFF;
            padding: 1.5cm;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            -webkit-box-sizing: initial;
            -moz-box-sizing: initial;
            box-sizing: initial;
            page-break-after: always
        }

        .page-landscape * {
            font-family: arial;
            font-size: 11px
        }

        .footer {
            position: absolute;
            bottom: 1.5cm;
            left: 1.5cm;
            right: 1.5cm;
            width: auto;
            height: 30px
        }


        .it-grid {
            background: #FFF;
            border-collapse: collapse;
            border: 1px solid #000
        }

        .it-grid th {
            color: #000;
            border: 1px solid #000;
            border-top: 1px solid #000;
            background: #05405a;
            color: white;
            padding: 3px;
            border: 1px solid #000
        }

        .it-grid tr:nth-child(even) {
            background: #f8f8f8
        }

        .it-grid td {
            padding: 3px;
            border: 1px solid #000
        }


        .it-cetak td {
            padding: 6px 5px
        }


        .detail {
            margin-top: 10px;
            margin-bottom: 10px
        }

        .detail td {
            padding: 5px;
            font-size: 12px
        }

        .detail span {
            border-bottom: 1px solid black;
            display: inline-block;
            font-size: 12px
        }

        @media print {
            body {
                background: #fff;
                font-family: 'Times New Roman', Times, serif;
                font-size: 12pt
            }

            div {
                font-family: 'Times New Roman', Times, serif;
                font-size: 12pt
            }

            td {
                font-family: 'Times New Roman', Times, serif;
                font-size: 12pt
            }

            p {
                font-family: 'Times New Roman', Times, serif;
                font-size: 12pt
            }

            .page {
                height: 10cm;
                padding: 0.7cm;
                box-shadow: none;
                margin: 0
            }

            @page {
                size: A4;
                margin: 0;
                -webkit-print-color-adjust: exact
            }

            .page-landscape {
                height: 1cm;
                padding: 0.7cm;
                box-shadow: none;
                margin: 0
            }

            .footer {
                bottom: 0.7cm;
                left: 0.7cm;
                right: 0.7cm
            }
        }
    </style>
    <style type="text/css">
        @media print {
            @page {
                size: landscape
            }
        }

        .landscape {
            margin-top: 20px;
            margin-left: 10px;
            margin-right: 10px;
            margin-bottom: 20px;
        }

        .title2 {
            font-size: 30px;
        }

        .border-image {
            border: 1px solid black;
            border-radius: 10px;
        }

        .panjang {
            width: 180px;
        }
    </style>
</head>

<body>
    <div class="landscape">
        <!--/cover header-->
        <table width='100%' border="0">
            <tr>
                <td width='120'><img src='{{ asset('image/logo_tut.svg') }}' height='100'></td>
                <td>
                    <CENTER>
                        <strong class='f12' style="font-size: 25px;">
                            {{ $judul }} <br />
                            {{ strtoupper($sekolah) }}
                            <br>
                            {{ $ajaran }}
                        </strong>
                    </CENTER>
                </td>
                <td width='120'><img src='{{ asset('image/logo_sekolah.png') }}' height='100'></td>
            </tr>
        </table>
        <br>
        <hr>
        <!--/cover header-->
       @if($ttd == 1)
       <br>
       @else
        <table class='detail' border="0">
            <tr>
                <td>TAHUN</td>
                <td>:</td>
                <td><span style='width:150px;'>&nbsp; {{ $tahun }}</span></td>
                <td>BULAN</td>
                <td>:</td>
                <td><span style='width:150px;'>&nbsp; {{ strtoupper($bulan) }}</span></td>
            </tr>
        </table>
        @endif

        <table class='it-grid it-cetak' id="table_data" width='100%'>
            <thead style="background-color: #c7c7c7; font-weight: bold;">
                <tr height=40px>
                    <th width='2%' align=center>No</th>
                    <th width='25%'>NAMA</th>
                    <?php for ($i = 1; $i <= 31; $i++) {
                        echo "<th width='2%'>" . $i . '</th>';
                    } ?>
                    <th rowspan="2" width='1%'>H</th>
                    <th rowspan="2" width='1%'>A</th>
                    <th rowspan="2" width='1%'>I</th>
                    <th rowspan="2" width='1%'>T</th>
                    <th rowspan="2" width='1%'>S</th>
                    {{-- <th width='1%'>U</th>
                        <th width='1%'>L</th>
                        <th width='1%'>K</th> --}}
                    <!-- <th width='4%'>%</th> -->
                </tr>

                <tr height=40px>
                    <th width='2%' align=center></th>
                    <th width='25%'></th>
                    <?php for ($i = 1; $i <= 31; $i++) {
                        echo "<th width='2%' style='font-size:10px; writing-mode: vertical-rl;'>" . hariIndo3($thn_bulan2 . $i) . '</th>';
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                @foreach ($absen as $data)
                <tr>
                    <td>{{ $no++ }}</td></td>
                    <td>{{ $data->hgNamaFull}}</td> 
                    @for ($i=1; $i <=31 ; $i++)
                    <td {{ colorTgl($data['tgl' . $i]) }} align="center">{{ $data['tgl' . $i] }}</td>
                    @endfor
                    <td align="center">{{ $data->HADIR }}</td>
                    <td align="center">{{ $data->ALPHA }}</td>
                    <td align="center">{{ $data->IZIN }}</td>
                    <td align="center">{{ $data->TERLAMBAT }}</td>
                    <td align="center">{{ $data->SAKIT }}</td>
                </tr>
                   
                @endforeach
            </tbody>
        </table>
        
        @if($ttd==1)
         <!-- BAGIAN TANDA TANGGAN -->
            <div style='padding-left: 50px;'>
                <style type="text/css">
                    .panjang{
                        width: 180px;
                    }
                    
                </style>
                <br><br>
                <table border='0' width='100%'>
                    <tr>
                        <td width='60%'></td>
                        <td >{{ $kecamatan }}, {{ $tgl }}</td>
                    </tr>
                    
                    <tr>
                        <td></td>
                        <td>{{ $kepsek }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="panjang"><br><br><br><strong></strong></td>
                        <td class="panjang"><br><br><br><strong>{{ $namaKepsek }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td >NIP. {{ $nikepsek }}</td>
                    </tr>
                </table>
            </div>
        <!-- /BAGIAN TANDA TANGGAN -->
        @endif
    </div>

    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var printHeader = $('#print-header-wrapper').html();
            var div_pageBreaker = '<div style="page-break-before:always;"></div>';
            var per_page = 25;
            $('#table_data').each(function(index, element) {
                //how many pages of rows have we got?
                var pages = Math.ceil($('tbody tr').length / per_page);

                //if we only have one page no more
                if (pages == 1) {
                    return;
                }
                //get the table we're splutting
                var table_to_split = $(element);

                var current_page = 1;
                //loop through each of our pages
                for (current_page = 1; current_page <= pages; current_page++) {
                    //make a new copy of the table
                    var cloned_table = table_to_split.clone();
                    //remove rows on later pages
                    $('tbody tr', table_to_split).each(function(loop, row_element) {
                        //if we've reached our max
                        if (loop >= per_page) {
                            //get rid of the row
                            $(row_element).remove();
                        }
                    });

                    //loop through the other copy
                    $('tbody tr', cloned_table).each(function(loop, row_element) {
                        //if we are before our current page
                        if (loop < per_page) {
                            //remove that one
                            $(row_element).remove();
                        }
                    });

                    //insert the other table afdter the copy
                    if (current_page < pages) {
                        $(div_pageBreaker).appendTo('#lastDataTable');
                        $(printHeader).appendTo('#lastDataTable');
                        $(cloned_table).appendTo('#lastDataTable');
                    }

                    //make a break
                    table_to_split = cloned_table;
                }
            });
        });
    </script>
</body>

</html>
