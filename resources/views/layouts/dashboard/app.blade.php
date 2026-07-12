<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
GERIP - Global Export Risk Platform
</title>


@vite([
'resources/css/app.css',
'resources/js/app.js'
])


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link 
rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"
/>

<style>


*{
    box-sizing:border-box;
}


body{

    background:#020617 !important;

    color:#f8fafc !important;

    font-family:'Segoe UI',sans-serif;

}



/* SIDEBAR */

.sidebar{

    width:260px;

    height:100vh;

    position:fixed;

    background:#0f172a;

    left:0;

    top:0;

    padding:25px 15px;

    border-right:1px solid #1e293b;

}



.logo{

    font-size:25px;

    font-weight:700;

    color:#f8fafc;

}



.logo span{

    display:block;

    font-size:12px;

    color:#94a3b8;

    margin-top:5px;

}





.menu{

    margin-top:35px;

}



.menu a{


    display:flex;

    align-items:center;

    padding:13px 16px;

    margin-bottom:10px;

    border-radius:12px;

    color:#cbd5e1;

    text-decoration:none;

    transition:.3s;

}



.menu a:hover,
.menu .active{


    background:

    linear-gradient(
        135deg,
        #2563eb,
        #06b6d4
    );


    color:white;


    transform:translateX(5px);


    box-shadow:

    0 8px 20px rgba(37,99,235,.35);


}

/* MAIN CONTENT */


.content{


    margin-left:260px;

    padding:30px;

}

.topbar{


    height:65px;

    background:#0f172a;

    border:1px solid #1e293b;

    border-radius:15px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 25px;

    margin-bottom:25px;


    color:white;

}

/* CARD */


.card-dark{

    background:#111827 !important;

    border:1px solid #1e293b !important;

    border-radius:18px;

    color:#f8fafc !important;

    position:relative;

    overflow:hidden;

    transition:
    transform .3s ease,
    box-shadow .3s ease,
    border-color .3s ease;

}

/* HOVER EFFECT CARD */


.card-dark:hover{


    transform:translateY(-8px);


    border-color:transparent !important;


    box-shadow:

    0 0 20px rgba(37,99,235,.35),

    0 20px 40px rgba(0,0,0,.5);


}

/* GRADIENT BORDER EFFECT */


.card-dark::before{


    content:"";

    position:absolute;

    inset:0;


    border-radius:18px;


    padding:1px;


    background:

    linear-gradient(
        135deg,
        #2563eb,
        #06b6d4,
        #22c55e
    );


    -webkit-mask:

    linear-gradient(#fff 0 0)
    content-box,

    linear-gradient(#fff 0 0);


    -webkit-mask-composite:
    xor;


    mask-composite:
    exclude;


    opacity:0;


    transition:.3s;


}

.card-dark:hover::before{


    opacity:1;


}

</style>


</head>


<body>



<!-- SIDEBAR -->


<div class="sidebar">


<div class="logo">

🌍 GERIP

<span>
Global Export Risk Platform
</span>


</div>




<div class="menu">


<a href="{{route('dashboard')}}"
class="active">

🌍 Dashboard

</a>



<a href="#">

🌎 Country Monitor

</a>



<a href="#">

⚠ Risk Analytics

</a>



<a href="#">

☁ Weather Monitoring

</a>



<a href="#">

💱 Currency Intelligence

</a>



<a href="#">

📰 Global News

</a>



<a href="#">

⚓ Port Monitoring

</a>



<a href="#">

📊 Analytics

</a>



<a href="#">

🔄 Country Comparison

</a>



<a href="#">

⭐ Watchlist

</a>



</div>


</div>





<!-- CONTENT -->


<div class="content">


<div class="topbar">


<h5>
Executive Dashboard
</h5>


<div>

Welcome,
{{Auth::user()->name}}

</div>


</div>



{{ $slot }}



</div>


<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</body>


</html>