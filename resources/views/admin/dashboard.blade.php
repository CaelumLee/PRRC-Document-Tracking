@extends('layouts.app')

@section('content')
@include('inc.adminSideNav')
<div class="main">
    <!-- <div class="container"> -->
        <div class="row">
            <div class="col s12">
                <h2>Dashboard for {{Auth::user()->department->name}}</h2>
            </div>

            <div class="col l3 m6 s12">
                <div class="card" style ="border : 2px solid green;">
                    <div class="card-stacked">
                        <div class="card-content">
                            <data-counter
                            icon="create"
                            v-bind:start="0"
                            v-bind:end="{{$data_values['a']}}"
                            ></data-counter>
                            <p>Total records created</p>
                            <p>for this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col l3 m6 s12">
                <div class="card" style ="border : 2px solid gray;">
                    <div class="card-stacked">
                        <div class="card-content">
                            <data-counter
                            icon="error_outline"
                            v-bind:start="0"
                            v-bind:end="{{$data_values['b']}}"
                            ></data-counter>
                            <p>Total records inactive</p>
                            <p>for this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col l3 m6 s12">
                <div class="card" style ="border : 2px solid yellow;">
                    <div class="card-stacked">
                        <div class="card-content">
                        <data-counter
                            icon="check_box"
                            v-bind:start="0"
                            v-bind:end="{{$data_values['c']}}"
                            ></data-counter>
                            <p>Total approved documents</p>
                            <p>for this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col l3 m6 s12">
                <div class="card" style ="border : 2px solid red;">
                    <div class="card-stacked">
                        <div class="card-content">
                            <data-counter
                            icon="archive"
                            v-bind:start="0"
                            v-bind:end="{{$data_values['d']}}"
                            ></data-counter>
                            <p>Total archived documents</p>
                            <p>for this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col s12">
                <div class="card" style ="border : 2px solid black;">
                <bar-chart url="{{route('DocuJson')}}"></bar-chart>
                </div>
            </div>
            

            <!-- <div class="col s7">
                <table class="dashboard-table" id="users-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>1</th>
                            <th>Jayson Lee</th>
                            <th>Admin</th>
                            <th>icons?</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col s5">
                <table class="dashboard-table" id="holidays-table">
                    <thead>
                        <tr>
                            <th>Holiday date</th>
                            <th>Holiday name</th>
                            <th>options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>01-01</th>
                            <th>New-Year's Day</th>
                            <th>icons?</th>
                        </tr>
                    </tbody>
                </table>
            </div> -->
            
        </div>
    <!-- </div> -->
</div>

@stop

@push('scripts')
<script src="{{asset('js/dashboard.js')}}"></script>
<script>
    $('.dashboard-table').DataTable({
        pagingType: "simple",
        dom: '<div>pt',
        language:{
            paginate:{
                previous: "<i class='material-icons'>chevron_left</i>",
                next: "<i class='material-icons'>chevron_right</i>"
            }
        }
    });
</script>
@endpush