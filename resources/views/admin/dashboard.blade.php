@extends('layouts.app')

@section('content')
@include('inc.snav')
<div class="main">
    <!-- <div class="container"> -->
        <div class="row">
            <div class="col s12">
                <h2>Dashboard for this department</h2>
            </div>

            <div class="col l3 m6 s12">
                <div class="card">
                    <div class="card-stacked">
                        <div class="card-content">
                            <p>Total records created for this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col l3 m6 s12">
                <div class="card">
                    <div class="card-stacked">
                        <div class="card-content">
                            <p>Total inactive doucs for this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col l3 m6 s12">
                <div class="card">
                    <div class="card-stacked">
                        <div class="card-content">
                            <p>Total approved docus for this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col l3 m6 s12">
                <div class="card">
                    <div class="card-stacked">
                        <div class="card-content">
                            <p>Di ko pa alam paano</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col s7">
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
            </div>
            
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