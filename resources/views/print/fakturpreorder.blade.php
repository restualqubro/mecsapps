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
    #watermark {
      color: rgba(128, 128, 128, 0.3);
      height: 100%;
      left: 0;
      line-height: 2;
      margin: 0;
      position: fixed;
      top: 0;
      transform: rotate(-30deg);
      transform-origin: 0 100%;
      width: 3000px;
      word-spacing: 10px;
      z-index: 1;
      -webkit-touch-callout: none; /* iOS Safari */
      -webkit-user-select: none; /* Safari */
      -khtml-user-select: none; /* Konqueror HTML */
      -moz-user-select: none; /* Old versions of Firefox */
      -ms-user-select: none; /* Internet Explorer/Edge */
      user-select: none; /* Non-prefixed version, currently
      supported by Chrome, Edge, Opera and Firefox */
      }
}

@media {
  #watermark {
    color: rgba(128, 128, 128, 0.3);
    height: 100%;
    left: 0;
    line-height: 2;
    margin: 0;
    position: fixed;
    top: 0;
    transform: rotate(-30deg);
    transform-origin: 0 100%;
    width: 3000px;
    word-spacing: 10px;
    z-index: 1;
    -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
    -khtml-user-select: none; /* Konqueror HTML */
    -moz-user-select: none; /* Old versions of Firefox */
    -ms-user-select: none; /* Internet Explorer/Edge */
    user-select: none; /* Non-prefixed version, currently
    supported by Chrome, Edge, Opera and Firefox */
    }
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
    h4.code {
        margin : 0;
        color: rgb(177, 7, 44);
        font-weight: bold;
    }
    .box {
        background-color: rgb(182, 182, 182);
    }    
    tr,td {
      font-size : 10pt;
      border-color: aliceblue;      
    }
}
</style>
  
</head>
<body>
  <div class="container">
    <div class="row">        
      <div class="col-8">
        <div class="row">                
          <div class="col-12">
            <p class="small">Perbaikan, Perawatan dan Penjualan <br>
              Komputer, Laptop, Printer, UPS, TV/Monitor<br/>
              Recovery Harddisk
            </p>
            <h4>{{$title}}</h4>
            <br/>
            <div class="row">
              <div class="col-2">
                Tanggal<br/>
                Customer<br/>
                Contact<br/>
              </div>
              <div class="col-0">
                :<br/>
                :<br/>
                :<br/>
              </div>
              <div class="col-8">              
                {{date('d M Y', strtotime($items->created_at))}}<br/>
                {{$items->customer->name}}<br/>
                {{$items->customer->telp}}<br/>              
              </div>
            </div>
            <br/>
            <div class="row">
              <div class="col-4">
                <table class="table table-bordered">
                  <th><h4 class="code">{{$items->code}}</h4></th>
                </table>
              </div>
              <div class="col-8"></div>
            </div>
          </div>   
        </div>
      </div>          
      <div class="col-4">
        <div class="float-right"><img src="{{$logo}}" style="max-width:200px"/></div>
        <div class="float-right">
          <p class="highlight small text-right font-bold">
            Jalan Manggis no 47, Banjarmasin <br/>
            0812 5078 6262 <br/>
            {{"@mecsbjm"}} <br/>
            fb.com/mecskom
          </p>
        </div>
      </div>          
      <table class="table table-bordered">
        <thead>
          <tr>                                        
            <th width="25%">Nominal DP</th>
            <th width="20%">Estimasi Waktu</th>                                
            <th width="55%">Items / Keterangan</th>
          </tr>
        </thead>
        <tbody>           
          <tr>                                    
            <td>{{number_format($items->nominal, 0, '', '.')}}</td>
            <td>{{$items->estimasi}} Hari / Days</td>
            <td>{{$items->description}}</td>            
          </tr>           
        </tbody>
      </table>             
      <div class="row">
        <div class="col-6">
          <p class="highlight small">
            Terima Kasih<br/>
            Lampiran ini digunakan sebagai alat bukti pembayaran yang sah sebagai  pada Transaksi Preorder pada Mecs Komputer Banjarmasin
            <br/><br/>Metode Pembayaran : Cash / Tunai, Transfer
          </br>
          @foreach($banks as $bank)
          - {{$bank->bank_name}} : {{$bank->number}} an. {{$bank->name}}
          @endforeach
        </p>
          </p>
        </div>            
        <div class="col-6">
          <div class="row">
            <div class="col-6">
              <div class="text-center">
                Hormat Kami<br/><br/><br/><br/>(Mecs Komputer)
              </div>
            </div>
            <div class="col-6">
              <div class="text-center">
                Customer<br/><br/><br/><br/>(..........................)
              </div>
            </div>
          </div>                
        </div>
      </div>                                 
    </div>
  </div>  


<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<!-- AdminLTE App -->
</body>
</html>
