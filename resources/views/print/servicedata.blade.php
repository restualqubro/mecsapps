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
                    <br/>   
                    <p>Total Items : {{$count}}<br/>
                    Printed at : {{$dateTime}}<br/>
                    Printed by : {{$users}}</p>                 
                </div>   
            </div>
          </div>          
          <div class="col-4">
            <div class="float-right"><img src="{{$logo}}" style="max-width:200px"/></div>            
          </div>
        </div>
                
        <table class="table table-bordered">
          <thead>
            <tr>                            
              <th width="10%">Code</th>
              <th width="20%">Customer</th>
              <th width="10%">Tanggal Masuk</th>
              <th width="15%">Kategori</th>              
              <th width="15%">Merk/Seri</th>
              <th width="15%">Seri/Tipe</th>
              <th width="15%">Status</th>
            </tr>
          </thead>
          <tbody> 
            @foreach($item as $items)
            <tr>
                <td>{{$items->code}}</td>
                <td>{{$items->customer->name}}</td>
                <td>{{$items->created_at->toDateString()}}</td>
                <td>{{$items->category->name}}</td>
                <td>{{$items->merk}}</td>
                <td>{{$items->seri}}</td>
                <td>{{$items->status}}</td>
            </tr>   
            @endforeach        
          </tbody>
        </table>                   
    </div>


<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<!-- AdminLTE App -->




</body>
</html>
