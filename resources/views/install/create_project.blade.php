<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Backend Install</title>
    <link href="{{asset('custom/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('back/plugins/select2/css/select2.min.css')}}">

  <link rel="stylesheet" href="{{asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

  </head>
  <body>
    

    <div class="container mt-5">
      <div class="card">
      	<div class="card-header">
      	   <h5 class="text-center">Project Details</h5>
      	</div>

      	<div class="card-body">
      	  <form class="" action="{{url('store-info')}}" method="POST">
                  @csrf

 
                  <div class="row mb-3">
                    <label for="domain_url" class="col-sm-2 col-form-label">Your Domain URL</label>
                    <div class="col-sm-12">
                      <input type="url" name="domain_url" class="form-control" id="domain_url" placeholder="Example: http://yourdomain.com" required="">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="timezone" class="col-sm-2 col-form-label">Select Timezone</label>
                    <div class="col-sm-12">
                      <select id="timezone" name="timezone" class="form-control select2bs4" required="">
                            <option value="" selected="" disabled="">Select Timezone</option>
                            @foreach(timezones() as $timezone)
                              <option value="{{$timezone}}">{{$timezone}}</option>
                            @endforeach
                      </select>
                    </div>
                  </div> 


                  <div class="row mb-3">
                    <label for="db_name" class="col-sm-2 col-form-label">Database Name</label>
                    <div class="col-sm-12">
                      <input type="text" name="db_name" class="form-control" id="db_name" placeholder="Database Name" required="">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="db_user" class="col-sm-2 col-form-label">Database User Name</label>
                    <div class="col-sm-12">
                      <input type="text" name="db_user" class="form-control" id="db_user" placeholder="Database User Name" required="">
                    </div>
                  </div>


                  <div class="row mb-3">
                    <label for="db_password" class="col-sm-2 col-form-label">Database Password</label>
                    <div class="col-sm-12">
                      <input type="password" name="db_password" class="form-control" id="db_password" placeholder="Database Password">
                    </div>
                  </div>

 
                  
                  <div class="row mb-3">
                    <div class="col-sm-12 text-center">
                      <button type="submit" class="btn btn-primary">Next Step </button>
                    </div>
                  </div>

                </form><!-- End General Form Elements -->

      	</div>
      </div>
    </div>
    

    <script src="{{asset('custom/custom_js.js')}}"></script>

    <!-- Select2 -->
    <script src="{{asset('back/plugins/select2/js/select2.full.min.js')}}"></script>

    <script src="{{asset('custom/select2.js')}}"></script> 

  </body>
</html>