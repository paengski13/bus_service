@extends("layout")
@section("content")
    <div class="row">
        <!-- Login-Form -->
        <div class="col-md-6 col-md-offset- col-sm-6 col-sm-offset-3">
            @if (isset($data['has_error']) && $data['has_error'])
            <div class="tab-pane fade in active" id="alert-1">
                <div class="margin-bottom-15"></div>       
                <div class="alert alert-danger fade in">
                    <strong>Error!</strong> {{ Session::get('error_message') }}
                </div>             
            </div>
            @endif
            {{ Form::open(array('action' => 'SessionController@requestLogin', 'method' => 'POST', 'class' => 'sky-form')) }}
                <header>Login form</header>
                
                <fieldset>                  
                    <section>
                        <div class="row">
                            <label class="label col col-4">Email</label>
                            <div class="col col-8">
                                <label class="input @if ($errors->has('user_email')) state-error @endif">
                                    <i class="icon-append fa fa-user"></i>
                                    <input type="text" name="user_email" value="{{ Input::old('user_email') }}">
                                    {{ $errors->first('user_email', '<em for="user_email" class="invalid error-msg">:message</em>') }}
                                </label>
                            </div>
                        </div>
                    </section>
                    
                    <section>
                        <div class="row">
                            <label class="label col col-4">Password</label>
                            <div class="col col-8">
                                <label class="input @if ($errors->has('password')) state-error @endif">
                                    <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="password" class="invalid">
                                    {{ $errors->first('password', '<em for="password" class="invalid error-msg">:message</em>') }}
                                    
                                </label>
								@if (isset($data["window_id"]))
                                <div class="note"><a href="{{ URL::to('login') }}" class="modal-opener">login to your account: {{ $data["window_id"] }}</a></div>
								@endif
                            </div>
                        </div>
                    </section>
                    
                </fieldset>
                <footer>
                    <button type="submit" class="btn-u">Log in</button>
                </footer>
            </form>         
            
            <form action="" id="sky-form1" class="sky-form sky-form-modal">
                <header>Password recovery</header>
                
                <fieldset>                  
                    <section>
                        <label class="label">E-mail</label>
                        <label class="input">
                            <i class="icon-append icon-user"></i>
                            <input type="email" name="email" id="email">
                        </label>
                    </section>
                </fieldset>
                
                <footer>
                    <button type="submit" name="submit" class="button">Submit</button>
                    <a href="#" class="button button-secondary modal-closer">Close</a>
                </footer>
                    
                <div class="message">
                    <i class="rounded-x fa fa-check"></i>
                    <p>Your request successfully sent!<br><a href="#" class="modal-closer">Close window</a></p>
                </div>
            {{ Form::close() }}
        </div>
        <!-- End Login-Form -->
    </div>
@stop