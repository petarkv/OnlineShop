@extends('layouts.front_layout.front_design')
@section('content')

<div class="span9">
    <ul class="breadcrumb">
		<li><a href="index.html">Home</a> <span class="divider">/</span></li>
		<li class="active">My Account</li>
    </ul>
	<h3> {{ Auth::user()->name }} - Account</h3>	
	<hr class="soft"/>
    
    @if(Session::has('success_message'))
        <div class="alert alert-success" role="alert" style="margin-top: 10px;">
            <strong>{{ Session::get('success_message') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Session::has('error_message'))
        <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
            <strong>{{ Session::get('error_message') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-top: 10px; height: 50px;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    @endif

	<div class="row">
		<div class="span4">
			<div class="well">
                <h5>Contact Details</h5><br/>
                Enter your contact details.<br/><br/><br/>
                <form id="accountForm" action="{{ url('/account') }}" method="post">@csrf
                <div class="control-group">
                    <label class="control-label" for="name">Name</label>
                    <div class="controls">
                        <input class="span3"  type="text" id="name" name="name" placeholder="Enter Name" 
                        value="{{ $userDetails['name'] }}" pattern="[a-zA-Z]+">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">E-mail address</label>
                    <div class="controls">
                        <input class="span3" readonly="" value="{{ $userDetails['email'] }}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="address">Address</label>
                    <div class="controls">
                        <input class="span3"  type="text" id="address" name="address" placeholder="Enter Address" value="{{ $userDetails['address'] }}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="city">City</label>
                    <div class="controls">
                        <input class="span3"  type="text" id="city" name="city" placeholder="Enter City" value="{{ $userDetails['city'] }}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="country">Country</label>
                    <div class="controls">                        
                        <select class="span3" id="country" name="country">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country['country_name'] }}"
                                @if($country['country_name']==$userDetails['country']) selected="" @endif>{{ $country['country_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="postal_code">Postal Code</label>
                    <div class="controls">
                        <input class="span3"  type="text" id="postal_code" name="postal_code" placeholder="Enter Postal Code" value="{{ $userDetails['postal_code'] }}">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="mobile">Mobile</label>
                    <div class="controls">
                        <input class="span3"  type="text" id="mobile" name="mobile" placeholder="Enter Mobile" value="{{ $userDetails['mobile'] }}">
                    </div>
                </div>                
                <div class="controls">
                <button type="submit" class="btn block">Update</button>
                </div>
                </form>
		    </div>
		</div>
		<div class="span1"> &nbsp;</div>
		<div class="span4">
			<div class="well">
                <h5>UPDATE PASSWORD</h5><br/>
                <form id="passwordForm" action="{{ url('/update-user-password') }}" method="POST">@csrf
                    <div class="control-group">
                        <label class="control-label" for="current_password">Current Password</label>
                        <div class="controls">
                            <input class="span3"  type="password" id="current_password" name="current_password" placeholder="Enter Current Password">
                            <br><span id="checkPassword"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="new_password">New Password</label>
                        <div class="controls">
                            <input class="span3"  type="password" id="new_password" name="new_password" placeholder="Enter New Password">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="confirm_password">Confirm Password</label>
                        <div class="controls">
                            <input class="span3"  type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn">Update Password</button>
                        </div>
                    </div>
                </form>
		    </div>
		</div>
	</div>	
</div>

@endsection