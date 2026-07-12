<x-app-layout>


<x-slot name="header">

<h2>
Global Country Monitoring
</h2>

</x-slot>



<div class="container mt-4">


<div class="card shadow">


<div class="card-body">


<form method="GET">


<div class="row">


<div class="col-md-5">

<input 
type="text"
name="search"
class="form-control"
placeholder="Search country..."
value="{{request('search')}}">

</div>



<div class="col-md-4">

<select 
name="region"
class="form-control">


<option value="">
All Region
</option>


@foreach($regions as $region)

<option value="{{$region}}">
{{$region}}
</option>

@endforeach


</select>

</div>



<div class="col-md-3">

<button class="btn btn-primary">
Search
</button>


</div>


</div>


</form>


</div>


</div>



<br>



<div class="row">


@foreach($countries as $country)


<div class="col-md-3 mb-3">


<div class="card shadow">


<div class="card-body">


<h5>

{{$country->name}}

</h5>



<p>

{{$country->region}}

</p>



<a href="{{route('countries.show',$country->id)}}"
class="btn btn-sm btn-dark">

Detail

</a>


</div>


</div>


</div>


@endforeach


</div>


{{$countries->links()}}



</div>


</x-app-layout>