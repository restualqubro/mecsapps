<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>App | {{ $title; }} </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">    
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- Google Font: Source Sans Pro -->
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

<style>
* {
    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
    color-adjust: exact !important;                 /*Firefox*/
    font-family: Poppins;
}
@media print{
    p.small {
        line-height: 1;        
    }
    p.highlight {
        background-color: rgb(189, 189, 189);
        padding: 0.7rem;
        border-radius: 0.3rem;
        line-height: 1.5;
    }
    h4 {
        margin-top : -1rem;
        margin-bottom : -1.5rem;
        color: rgb(177, 7, 44);
        font-weight: bold;
    }
    .box {
        background-color: rgb(182, 182, 182);
    }
    td {
        font-size : 10pt;
    }
}

@media {
    p.small {
        line-height: 1; 
        font-size: 0.8rem;       
    }
    p.highlight {
        background-color: rgb(189, 189, 189);
        padding: 0.7rem;
        border-radius: 0.3rem;
        line-height: 1.5;
    }
    h4 {
        margin-top : -1rem; 
        margin-bottom : -1.5rem;
        color: rgb(177, 7, 44);
        font-weight: bold;
    }
    h3.code {
        margin : 0;
        color: rgb(177, 7, 44);
        font-weight: bold;
    }
    .box {
        background-color: rgb(182, 182, 182);
    }
    th,td {
        font-size : 10pt;
    }
}
</style>
  
</head>
<body>

    <div class="container">
        <div class="row">        
          <div class="col-8">
            <div class="row">
              {{-- <div class="col-12">
                <img src="{{$logo}}" style="max-width:100px"/>
                <div class="box"><br/>Jl. Manggis No 47, Banjarmasin<br/>
                    0851 0042 2583</div>
              </div>               --}}
                <div class="col-12">
                    <p class="small">Perbaikan, Perawatan dan Penjualan <br>
                        Komputer, Laptop, Printer, UPS, TV/Monitor<br/>
                        Recovery Harddisk
                    </p>
                    <h4>{{$title}}</h4>
                    <br/>                                            
                </div>   
            </div>
          </div>          
          <div class="col-4">
            <div class="float-right"><img src="{{$logo}}" style="max-width:200px"/></div>            
          </div>
        </div>
        {{-- <div class="row">
            <div class="card mx-4" style="width: 45%;">                
                <div class="card-body">
                <h5 class="card-title font-bold">Saldo Cash</h5>
                <p class="card-text">Rp 100.000.000,-</p>                
                </div>
            </div>          
            <div class="card mx-4" style="width: 45%;">                
                <div class="card-body">
                <h5 class="card-title font-bold">Saldo Cash</h5>
                <p class="card-text">Rp 100.000.000,-</p>                
                </div>
            </div>          
        </div> --}}
            
        <table class="table table-bordered">
            <thead>
              <tr>                            
                <th>NAMA TRANSAKSI</th>                
                <th>DEBIT</th>
                <th>KREDIT</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                  <td>Saldo Cash</td>
                  <td>Rp {{number_format($saldoCash, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td>Saldo Mandiri</td>
                  <td>Rp {{number_format($saldoMandiri, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                    <td>Invoice Cash</td>
                    <td>Rp {{number_format(0, 0, '', '.')}}</td>
                    <td>Rp {{number_format($invoice, 0, '', '.')}}</td>
                </tr>   
                <tr>
                  <td>Sales Cash</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                  <td>Rp {{number_format($sales, 0, '', '.')}}</td>
                </tr>   
                <tr>
                  <td>Setoran Tunai</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                  <td>Rp {{number_format($pemasukan, 0, '', '.')}}</td>
                </tr>  
                <tr>
                  <td>Pelunasan Piutang Sales</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                  <td>Rp {{number_format($pelunasanSales, 0, '', '.')}}</td>
                </tr>                      
                <tr>
                  <td>Pelunasan Piutang Invoice</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                  <td>Rp {{number_format($pelunasanInvoices, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td>Purchase Cash</td>
                  <td>Rp {{number_format($purchase, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td>Service to Partner</td>
                  <td>Rp {{number_format($topartner, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td>Pelunasan Purchase</td>
                  <td>Rp {{number_format($pelunasanPurchase, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td>Retur Service</td>
                  <td>Rp {{number_format($returService, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td>Kompensasi</td>
                  <td>Rp {{number_format($compensation, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td>Pengeluaran</td>
                  <td>Rp {{number_format($allcashout, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td>Penarikan Tunai</td>
                  <td>Rp {{number_format($penarikan, 0, '', '.')}}</td>
                  <td>Rp {{number_format(0, 0, '', '.')}}</td>
                </tr>
                <tr>
                  <td><b>Total</b></td>
                  <td><b>Rp {{number_format($saldoCash + $saldoMandiri + $purchase + $topartner 
                          + $pelunasanPurchase + $returService + $compensation
                          + $allcashout + $penarikan, 0, '', '.')}}</b></td>
                  <td><b>Rp {{number_format($invoice + $sales + $pemasukan + $pelunasanSales 
                        + $pelunasanInvoices, 0, '', '.')
                        }}</b></td>
                </tr>
            </tbody>
          </table>           
            <p><small>Printed at : {{$dateTime}}      
              by : {{Auth::user()->name}}</small></p>      
            <p>                 
    </div>


<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<!-- AdminLTE App -->




</body>
</html>
