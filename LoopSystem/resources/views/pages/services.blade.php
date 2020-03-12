@extends('includes.new_template')

@section('content')
<div ng-app="myServiceApp" ng-controller="myServiceController" class="card card-content">
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">

          <h3 class="m-0 text-dark"><i class="nav-icon fas fa-box"></i> Services</h3>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->





    <div class="card">
      <div class="card-header">
        <button type="button" class="waves-effect waves-light btn-small" data-toggle="modal" data-target="#modal-default">
        <i class="material-icons">add</i>Add Service
        </button>
      </div>

      <!-- /.card-header -->
      <div class="card-body">

        <div class="row">
        <div class="col-sm-2">
                <label>Records per page</label>
                <select class="browser-default" ng-model="data_limit">
                    <option>10</option>
                    <option>20</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div> <br/>

            <div class="input-field col s6  right">
                <label for="search">Search</label>
                <input type="text" ng-model="search" ng-change="filter()" id="search" class="form-control" />
            </div>
        </div>
        <br/>

        <div class="row">
            <div class="col-md-12" >
                <table class="highlight striped table-bordered">
                    <thead>
                        <th width="25%">Status&nbsp;<a ng-click="sort_with('Status');"><i class="material-icons">swap_vert</i></a></th>
                        <th width="25%">Name&nbsp;<a ng-click="sort_with('Name');"><i class="material-icons">swap_vert</i></a></th>
                        <th width="25%">Date Created&nbsp;<a ng-click="sort_with('Date');" style="cursor:text-menu"><i class="material-icons">swap_vert</i></a></th>
                        <th width="10%">&nbsp;</th>

                    </thead>
                    <tbody ng-show="filter_data > 0">
                        <tr ng-repeat="row in data = (file | filter:search | orderBy : base :reverse) | beginning_data:(current_grid - 1)* data_limit | limitTo:data_limit">
                            <td>

                                 <!-- Switch -->
                                    <div class="switch">
                                        <label>
                                        Inactive
                                        <input type="checkbox" ng-if="row.service_status == 'active'" checked class="green" ng-click="updateStatus(row.id, row.service_status, row.service_name)" data-toggle="modal" data-target="#modal-status">
                                        <input type="checkbox" ng-if="row.service_status == 'inactive'" ng-click="updateStatus(row.id, row.service_status, row.service_name)" data-toggle="modal" data-target="#modal-status">
                                        <span class="lever"></span>
                                        Active
                                        </label>
                                    </div>
                            </td>
                            <td>@{{ row.service_name}}</td>
                            <td>@{{row.created_at}}</td>
                            <td>
                                <button type="button" title="Edit" class="waves-effect waves-light btn-floating btn-small blue" id='' data-toggle="modal" data-target="#modal-edit" ng-click="fetchSingleData(row.id, row.service_name)">
                                    <i class="material-icons">edit</i>
                                </button>
                                <button type="button" title="Delete" class="waves-effect waves-light btn-floating btn-small red right" id='' data-toggle="modal" data-target="#modal-delete" ng-click="fetchDel(row.id, row.service_name)">
                                    <i class="material-icons">delete</i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot ng-show="filter_data == 0">
                        <th></th>
                        <th></th>
                        <th>No matching records found..</th>
                        <th></th>
                        <th></th>
                    </tfoot>

                </table>
            </div>
      </div>

      <div class="col-md-12">
        <div class="col-md-4">
            <p>Showing @{{data.length}} of @{{ entire_user}} entries</p>
        </div>
        <div class="col-md-6 pull-right" ng-show="filter_data > 0">
            <pagination page="current_grid"
                on-select-page="page_position(page)"
                boundary-links="true"
                total-items="filter_data"
                items-per-page="data_limit"

                class="pagination-small right-align"
                previous-text="&laquo;" next-text="&raquo;" style="cursor:context-menu">
            </pagination>
        </div>
    </div>


      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

<!-- add items modal -->
    <div class="modal" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger">
            <h4 class="modal-title">Add New Service</h4>
          </div>
          <form action="" method="POST">
          <div class="modal-body">
            <div class="form-group">
              {{ csrf_field() }}

              <div class="input-field col s6">
                <input type="text" name="serviceName" id="serviceName" ng-model="serviceName" class="validate" required>
                <label for="serviceName">Service Name</label>
              </div>
              <!-- <input type="text" name="serviceName" id="serviceName" ng-model="serviceName" placeholder="Service Name" required> -->
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="waves-effect waves-light btn-small red left" data-dismiss="modal">Cancel</button>
            <button type="button" class="waves-effect waves-light btn-small green right" name="submit" id='categoryAddBtn' ng-click="insertService()" >Save</button>
          </div>
        </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.add items modal -->


<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


     <!-- edit item modal -->
     <div class="modal fade" id="modal-edit">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header btn-danger">
            <h4 class="modal-title">Edit Service</h4>
          </div>
          <form action="" method="POST">
              {{ csrf_field() }}
              {{method_field('PATCH')}}
          <div class="modal-body" >
              <input type="hidden" class="form-control" id="eCatID" name="eCatID" value="" placeholder="Service ID" ng-model="id" required>
              <div class="form-group">
              <label>Service Name: @{{service}}</label>
              <input type="text" class="form-control" id="eCatName" name="eCatName" placeholder="Service Name" required ng-model="editServiceName">
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="waves-effect waves-light btn-small red left" data-dismiss="modal">Cancel</button>
            <button type="button" class="waves-effect waves-light btn-small blue right" id="categoryEditBtn" ng-click="editService()">Save changes</button>
          </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.edit item modal -->

          <!-- delete categories modal -->
    <div class="modal fade" id="modal-delete">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger">
            <h4 class="modal-title">Delete Service</h4>
          </div>
          {{-- <!-- <form action="{{route('catSoftDelete')}}" method="POST"> --> --}}
           {{ csrf_field() }}
          <div class="modal-body">
          <input type="hidden" id="dCatID" name="dCatID" ng-model="dServiceId" class="form-control">
          <h6 style="text-align:center">Are you sure you want to delete service <b ng-bind='dServiceName'></b>?</h6>
          </div>
          <div class="modal-footer">
            <button type="button" class="waves-effect waves-light btn-small red left" data-dismiss="modal">Cancel</button>
            <button type="button" class="waves-effect waves-light btn-small blue right" id='categoryDelBtn' ng-click='delService()'>Delete</button>
          </div>
          <!-- </form> -->
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.delete item modal -->

<!-- update status modal -->
    <div class="modal fade" id="modal-status">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger">
            <h4 class="modal-title">Change Service Status</h4>
          </div>
          {{-- <!-- <form action="{{route('catSoftDelete')}}" method="POST"> --> --}}
           {{ csrf_field() }}
          <div class="modal-body">
          <input type="hidden"  ng-model="sId" class="form-control">
          <input type="hidden"  ng-model="sStatus" class="form-control">
          <h6 style="text-align:center" ng-bind-html="confirmMessage"></h6>
          </div>
          <div class="modal-footer">
            <button type="button" class="waves-effect waves-light btn-small red left" data-dismiss="modal">Cancel</button>
            <button type="button" class="waves-effect waves-light btn-small blue right" id='categoryDelBtn' ng-click='changeStatus()'>Change</button>
          </div>
          <!-- </form> -->
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.update status  modal -->

    </div>
    <!-- /.end of ng-app and ng-controller -->

    </div>
    <!-- /.end of ng-app and ng-controller -->


<!-- Angular js -->
<!-- angular -->
<script src ="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.12/angular.min.js"></script>

<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js"></script> -->
  <script>
  var serviceApp = angular.module("myServiceApp", ['ui.bootstrap', 'ngSanitize']);

  serviceApp.filter('beginning_data', function(){
      return function(input, begin){
          if(input){
              begin = +begin;
              return input.slice(begin);
          }
          return [];
      }
  });

  serviceApp.controller("myServiceController", function($scope, $http, $timeout){
      //insert new service
    $scope.insertService = function(){
        $http.post(
            "insertService",
            {'serviceName':$scope.serviceName}
        ).then(function(){
            $scope.init();
            $('#modal-default').modal('hide');
            $('.modal-backdrop').remove();
            M.toast({html: 'Successfully Added!', classes: 'rounded'});
        });
    }

    //display service
    $scope.init= function(){
        $http.get('getServices').then(function(response){
        $scope.data = response.data;
        $scope.file = response.data;
        $scope.current_grid =1;
        $scope.data_limit = 10;
        $scope.filter_data = $scope.data.length;
        $scope.entire_user =  $scope.file.length;
    });
    }

    // fetch data to edit
    $scope.fetchSingleData = function(id, name){
        $scope.editServiceName = name;
        $scope.id = id;
    };

    //edit a service
    $scope.editService = function(){
        $http.post(
            'editService',
            {'serviceName':$scope.editServiceName, 'id':$scope.id}
        ).then(function(data){
            $scope.init();
            $('#modal-edit').modal('hide');
            $('.modal-backdrop').remove();
            M.toast({html: 'Successfully Updated!', classes: 'rounded'});
        })
    };

    // fetch data to delete
    $scope.fetchDel = function(id, name){
        $scope.dServiceId = id;
        $scope.dServiceName = name;
    }

    //delete a service
    $scope.delService = function(){
        $http.post(
            'deleteService',
            {'id':$scope.dServiceId}
        ).then(function(response){
            $scope.init();
            // $scope.page_position(page_number);
            $('#modal-delete').modal('hide');
            $('.modal-backdrop').remove();
            M.toast({html: 'Successfully Deleted!', classes: 'rounded'});
        })
    }

    // fecth data to update status
    $scope.updateStatus = function(id, status, name){
        $scope.init();
        $scope.sId = id;
        $scope.sStatus = status;
        if(status == 'inactive'){
            $scope.confirmMessage = 'Are you sure you want to change service <b>'+name+'</b> status from inactive to active?';
        }else{
            $scope.confirmMessage = 'Are you sure you want to change service <b>'+name+'</b> status from active to inactive?';
        }
    }
    // update service status
    $scope.changeStatus = function(){
        $http.post(
            'updateStatus',
            {'id':$scope.sId, 'status':$scope.sStatus}
        ).then(function(response){
            $scope.init();
            $('#modal-status').modal('hide');
            $('.modal-backdrop').remove();
            M.toast({html: 'Successfully Updated!', classes: 'rounded'});
        })
    }

    $scope.init();

    //pagination
    $scope.page_position = function(page_number){
        $scope.current_grid =page_number;
    }


    //filter in search
    $scope.filter = function(){
        $timeout(function(){
            $scope.filter_data = $scope.data.length;
        }, 20);

    }

    //reverse sort (arrow)
    $scope.sort_with = function(base) {
        $scope.base = base;
        $scope.reverse = !$scope.reverse;
    };

  });
  </script>

 @endsection


