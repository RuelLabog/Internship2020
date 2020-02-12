  @extends('includes/admin_template')



@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">

            <h1 class="m-0 text-dark"><i class="nav-icon fas fa-receipt"></i> Receipts</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Starter Page</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->





      <div class="card">
        <div class="card-header">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-receipt">
            <i class="fas fa-plus mr-2"></i>Add Receipt
          </button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="receipts_table" class="table table-bordered table-striped">
            <thead>
             <tr>
                  <th width="5%">#</th>
                  <th width="20%">Ornum</th>
                  <th width="20%">Purchase Date</th>
                  <th width="20%">Supplier</th>
                  <th width="12%">Total</th>
                  <th width="13%">Action</th>
                </tr>
            </thead>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->


<!-- add receipts modal -->
      <div class="modal fade" id="modal-add-receipt">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-danger">
              <h4 class="modal-title"><i class="fas fa-receipt mr-2"></i>Add New Receipt</h4>
            </div>
            <form action="" method="POST">
            <div class="modal-body">
              <div class="form-group">
                {{ csrf_field() }}
                <label>OR Number:</label>
                <input type="text" class="form-control" name="ornum" placeholder="Official Receipt Number" required>
              </div>
              <div class="form-group">
                <label>Supplier:</label>
                <input type="text" class="form-control" name="supplier" placeholder="Enter Supplier Name." required>
              </div>
              <div class="form-group">
                <label>Date of Purchase:</label>
                <input type="date" class="form-control" name="pdate"  required>
              </div>
              
              <div class="form-group">
                <label>Total:</label>
                <input type="text" class="form-control" name="total" placeholder="Total Amount" required>
              </div>


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success" name="submit" id='receiptAddBtn' onclick="receiptAdd()">Save</button>
            </div>
          </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.add receipt modal -->


       <!-- edit receipt modal -->
       <div class="modal fade" id="modal-edit-receipt">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header btn-danger">
              <h4 class="modal-title"><i class="fas fa-receipt mr-2"></i> Edit Receipt</h4>
            </div>
            <form action="" method="POST">
                {{ csrf_field() }}
                {{method_field('PATCH')}}
            <div class="modal-body">
                <input type="hidden" class="form-control" id="eRecID" name="eRecID" value="" required>
                <div class="form-group">
                <label>OR Number:</label>
                <input type="text" class="form-control" name="eOrnum" id="eOrnum" placeholder="Official Receipt Number" required>
              </div>
              <div class="form-group">
                <label>Supplier:</label>
                <input type="text" class="form-control" name="eSupplier" id="eSupplier" placeholder="Enter Supplier Name." required>
              </div>
              <div class="form-group">
                <label>Date of Purchase:</label>
                <input type="date" class="form-control" name="ePdate"  id="ePdate" required>
              </div>
              <!-- /.form group -->
              
              <div class="form-group">
                <label>Total:</label>
                <input type="text" class="form-control" name="eTotal" id="eTotal" placeholder="Total Amount" required>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success" id="receiptEditBtn" onclick="receiptEdit()">Save</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.edit receipt modal -->

            <!-- delete receipt modal -->
      <div class="modal fade" id="modal-delete-receipt">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-danger">
              <h4 class="modal-title">Delete Receipt</h4>
            </div>
            <form action="{{route('recSoftDelete')}}" method="POST">
             {{ csrf_field() }}
            <div class="modal-body">
            <input type="hidden" id="dRecID" name="dRecID" class="form-control">
            <h6 style="text-align:center">Are you sure you want to delete receipt <label id="dOrnum"></label>?</h6>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id='receiptDelBtn' onclick='receiptDel()'>Delete</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.delete receipt modal -->

    <script type="text/javascript">
       function receiptDel(){
            var id = $('#dRecID').val();
              $.ajax({
                type: 'POST',
                url: 'softDelRec',
                data: {'_token': $('input[name=_token').val(),
                        'dRecID': $('input[name=dRecID').val()},
                beforeSend:function(){
                    $('#receiptDelBtn').text('Deleting...');
                },
                success: function (response){
                    setTimeout(function(){
                        $('#modal-delete-receipt').modal('hide');
                        $('#receipts_table').DataTable().ajax.reload();
                        $('#receiptDelBtn').text('Delete');
                    }, 2000);
                }
            });
        }

        function receiptEdit(){
            var url =  "editRec";
            alert(pdate);
              $.ajax({
                type: 'POST',
                url: url,
                data: {'_token': $('input[name=_token').val(),
                        'eRecID':$('input[name=eRecID').val(),
                        'eOrnum':$('input[name=eOrnum').val(),
                        'eSupplier':$('input[name=eSupplier').val(),
                        'ePdate': $('input[name=ePdate').val(),
                        'eTotal':$('input[name=eTotal').val()
                        },
                beforeSend:function(){
                    $('#receiptEditBtn').text('Updating...');
                },
                success: function (response){
                    setTimeout(function(){
                        $('#modal-edit-receipt').modal('hide');
                        $('#receipts_table').DataTable().ajax.reload();
                        $('#receiptEditBtn').text('Save Changes');
                    }, 2000);
                }
            });
        }

        function receiptAdd(){
            $.ajax({
                type: 'POST',
                url: "{{ route('receiptInsert') }}",
                data: {
                        'ornum':$('input[name=ornum').val(),
                        'supplier':$('input[name=supplier').val(),
                        'pdate': $('input[name=pdate').val(),
                        'total':$('input[name=total').val()
                        },
                beforeSend:function(){
                    $('#receiptAddBtn').text('Inserting...');
                },
                success: function (response){
                    setTimeout(function(){
                        $('#modal-default').modal('hide');
                        $('#receipts_table').DataTable().ajax.reload();
                        $('#receiptAddBtn').text('Save Changes');
                    }, 2000);
                }
            });
        }
    </script>


 @endsection
