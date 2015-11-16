@extends("setup.layout")
@section("setup_content")
    <div class="col-md-10">
        <!--Table Bordered-->
        <div class="table-search-v1 panel panel-grey">
            <div class="panel-heading">
                <h3 class="panel-title pull-left"><i class="fa fa-location-up"></i> Bus Services (nearby area: <i class="fa fa-map-marker"></i>Sengkang)</i></h3>
                <div class="pull-right">
                    <div class="navbar-right">
                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#search_modal"><i class="fa fa-search"></i> Search</button>&nbsp;
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th width="1%"><p>#</p></th>
                            @if ($data['url_sort']['sort_by'] == 'location_name')
                            <th class="col-sm-4"><p><i class="{{ $shareView['order'][$data['url_sort']['order_by']]['class'] }}"></i> {{ link_to_action('BusServiceController@index', 'Location', array_merge($data['url_sort'], array('sort_by' => 'location_name'))) }}</p></th>
                            @else
                            <th class="col-sm-4"><p><i class="{{ $shareView['order']['']['class'] }}"></i> {{ link_to_action('BusServiceController@index', 'Location', array_merge($data['url_sort'], array('sort_by' => 'location_name'))) }}</p></th>
                            @endif
                            
                            <th class="col-sm-2"><p>Stop #</p></th>
                            <th class="col-sm-2"><p>Bus #</p></th>
                            <th class="col-sm-2"><p>Arrival Time</p></th>
                            <th width="1%"><p></p></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- check if at least 1 location is available -->
                        @if(!is_null($data['locations']) && $data['locations']->count() && count($data['locations']))
                            <!-- loop all location -->
                            @foreach($data['locations'] as $key => $location)
                                <!-- check if at least 1 stop is available in the location -->
                                @if ($location->Stop->count()) 
                                    <!-- loop all stop -->
                                    @foreach($location->Stop as $stop)
                                    
                                        <!-- check if at least 1 bus is available in the stop -->
                                        @if ($stop->Bus->count()) 
                                            <!-- loop all bus -->
                                            @foreach($stop->Bus as $bus)
                                                <tr>
                                                    <td><p>{{ $data['count']++ }}</p></td>
                                                    <td><p>{{ $location->location_name }}</p></td>
                                                    <td><p>{{ $stop->stop_name }} <br/>{{ $stop->stop_number }}</p></td>
                                                    <td><p>{{ $bus->bus_number }}</p></td>
                                                    <td><p id="update_{{ $bus->pivot->id }}">
                                                        @if ($bus->pivot->arrival_time == 1)
                                                            <label class="arriving">Arriving</label><br/>
                                                        @else
                                                            <label>{{ $bus->pivot->arrival_time }} minutes</label><br/>
                                                        @endif
                                                           {{ $bus->pivot->arrival_time2 }} minutes</p></td>
                                                    <td><p><button class="btn btn-success btn-xs btn_refresh" value="{{ $bus->pivot->id }}"><i class="fa fa-refresh"></i> Refresh</button></p></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Search Forms -->
            <div class="margin-bottom-40">
                {{ Form::open(array('action' => array('check.index'), 'method' => 'get', 'class' => 'sky-form')) }}
                    <div class="modal fade" id="search_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">Search</h4>
                                </div>
                                <div class="modal-body">
                                    <fieldset>
                                        <div class="row">
                                            <section class="col col-4"><label class="label"> Location</label></section>
                                            <section class="col col-6">
                                                <label class="select">
                                                    <select name="location_id">
                                                        <option value="0" selected>-- All -- </option>
                                                        @foreach($data["s_locations"] as $location)
                                                            @if($data['location_id'] == $location->id)
                                                                <option value="{{ $location->id }}" selected>{{ $location->location_name }}</option>
                                                            @else
                                                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </section>
                                        </div>
                                        
                                        <div class="row">
                                            <section class="col col-4"><label class="label"> Stop</label></section>
                                            <section class="col col-6">
                                                <label class="select">
                                                    <select name="stop_id">
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </section>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-u btn-u-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                    <button type="submit" class="btn-u"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
            <!-- End Search Forms -->
            
            <style>
                .progress-bar.animate {
                   width: 100%;
                }
            </style>
            
            <div class="modal js-loading-bar">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="progress progress-popup">
                                <div class="progress progress-striped active">
                                    <div class="progress-bar"></div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="hdn_stop_id" value="{{ $data['stop_id'] }}">
        </div>
        <!--End Table Bordered-->
    </div>
    
    <script type="text/javascript">
    $(document).ready(function () {
        
        // on change location, the bus stop will be updated
        $("select[name=location_id]").change(function (){
            // display loading bar
            $('.js-loading-bar').modal('show');
            $('.progress-bar').addClass('animate');
            getStop($(this).val());
        });
        
        // on click refresh for the retrieval of bus timing
        $(".btn_refresh").click(function() {
            // display loading bar
            $('.js-loading-bar').modal('show');
            $('.progress-bar').addClass('animate');

            bus_stop_id = $(this).val();
            $.ajax({
                url: $('input[name=base_url]').val() + '/bus_stop/' + bus_stop_id,
                type: 'get',
                cache: false,
                dataType: 'json',
                success: function (data) {
                    
                },
                complete: function (jqXHR, textStatus) {
                    var obj = jQuery.parseJSON(jqXHR.responseText);
                    
                    html_str = '';                    
                    if (obj.arrival_time == 1) {
                        html_str += '<label class="arriving">Arriving</label><br/>';
                    } else {
                        html_str += '<label>' + obj.arrival_time + ' minutes</label><br/>';
                    }
                    html_str += obj.arrival_time2 + ' minutes<br/>';

                    $("#update_" + bus_stop_id).html(html_str);
                    
                    // hide loading bar
                    $('.js-loading-bar').modal('hide');
                    $('.progress-bar').removeClass('animate');
                },
                error: function(xhr, textStatus, thrownError) {
                    console.log('Something went to wrong.Please Try again later...' + thrownError);
                }
            });
        });
        
        // loading animation
        $('.js-loading-bar').modal({
            backdrop: 'static',
            show: false
        });
        
        // get the stops on load
        getStop($("select[name=location_id]").val());
        
    });
    
    /**
     * call ajax function that will get the list of stops based on the location
     *
     */
    function getStop(location_id) {
        
        $.ajax({
            url: $('input[name=base_url]').val() + '/stop/location/' + location_id,
            type: 'get',
            cache: false,
            dataType: 'json',
            success: function (data) {
                
            },
            complete: function (jqXHR, textStatus) {
                var obj = jQuery.parseJSON(jqXHR.responseText);
                
                $("select[name=stop_id]").empty();
                $("select[name=stop_id]").append('<option value="0" selected>-- All -- </option>');
                
                $.each (obj, function (key, val) {
                    if ($("input[name=hdn_stop_id]").val() == val.id) {
                        $("select[name=stop_id]").append('<option value="' + val.id + '" selected>' + val.stop_name + '</option>');
                    } else {
                        $("select[name=stop_id]").append('<option value="' + val.id + '">' + val.stop_name + '</option>');
                    }
                });
                
                // hide loading bar
                $('.js-loading-bar').modal('hide');
                $('.progress-bar').removeClass('animate');
            },
            error: function(xhr, textStatus, thrownError) {
                console.log('Something went to wrong.Please Try again later...' + thrownError);
            }
        });
    }
    </script>
@stop