<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>@yield('pageTitle',config('app.name') )</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type=text/css href="/css/bootstrap/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
         .fc-month-view span.fc-title{
         white-space: normal;
         }
    </style>
</head>
<body>
    <!-- Navigation: Navbar -->
    <nav id="navbarMenu" class="navbar navbar-expand-md navbar-dark bg-dark sticky-top mb-2 box-shadow text-dark">
        <a class="navbar-brand" id="logo" href="#header">Event Calendar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Calendar</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" href="#">About Us</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>                       
            <form action="" class="navbar-nav mr-1 ml-1">
            <div class="v-line">
            </div>  
                <svg id="SearchIcon" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="grey" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>             
            </form>
        </div>
    </nav>
    <!--End Navigation-->
    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{route('calendar.store')}}" method="POST" class="needs-validation" novalidate>
                        {{ csrf_field() }}
                    <div class="modal-body">
                        <label for="category_id">Choose Category</label>
                        <select name="category_id" id="category_id">
                            @forelse ($categories as $category )
                                <option value={{ $category->id}}>{{ $category->name}}</option>
                            @empty
                                <option value="">No categories available</option>   
                            @endforelse                                                                                         
                        </select><br>
                        <label for="title">Title</label>
                        <input name="title" type="text" class="form-control @error('title') is-invalid @enderror" id="event_title" required>
                        <div class="invalid-feedback">Please enter a title!</div>
                        <label for="start_date">Start Date and Time</label>
                        <input type="datetime-local" class="form-control date @error('start_date') is-invalid @enderror" name="start_date" id="start_date" required>
                        <div class="invalid-feedback">Please enter a date!</div>
                        <label for="start_date">End Date and Time</label>
                        <input type="datetime-local" class="form-control date @error('end_date') is-invalid @enderror" name="end_date" id="end_date" required>
                        <div class="invalid-feedback">Please enter a date!</div>
                        <span id="endError" class="text-danger"></span>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="event_save_button" class="btn btn-primary" >Save Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--End Modal-->
    <!--Calendar-->
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{Session::get('success')}}
                    @php
                    Session::forget('success')
                    @endphp
                </div>
                @endif
                <button type="button" class="btn btn-success mb-2" id="add_event">Add Event</button>
                <div id="calendar">
                </div>
            </div>
        </div>
    </div>
    <!--End Calendar-->
    <!-- Footer -->
    <div class="footer bg-dark text-white" id="footer">
        <div class="container pt-5 pb-4">
            <div class="row my-4">
                <div class="col-sm-3">
                    <h4 class="mb-3">Navigation</h4>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-white">Home</a></li>
                        <li><a href="#pricing" class="text-white">Calendar</a></li>
                        <li><a href="#testimonials" class="text-white">About Us</a></li>
                        <li><a href="#contact" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h4 class="mb-3">About us</h4>
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
                </div>
                <div class="col-sm-3">
                    <h4 class="mb-3">Contact</h4>
                    <ul class="list-unstyled">
                        <li><span class="fa fa-envelope"></span> mail@example.com</li>
                        <li><span class="fa fa-phone-square"></span> +49 7543 123456</li>
                        <li><span class="fa fa-map-marker"></span> Hauptstraße 38</li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h4 class="mb-3">Privacy</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Cookies</a></li>
                        <li><a href="#" class="text-white">Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>  
    <!-- End Footer -->  
    <script>
         $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       
        var myevents = @json($events);

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center:'title',
                right: 'month,agendaWeek,agendaDay',
            },
            events: myevents,
            eventRender: function (event, element, view) {
                Object.values(event.category).forEach(function (key) {
                    var categories = Object.values(key);
                    element.find('.fc-title').append('<div class="hr-line-solid"></div><span style="font-size: 12px">' + categories + '</span></div>');
                });    
            },
            selectable: true,
            selectHelper: true,
            editable: true,
            eventDrop: function(event) {
                var id = event.id;
                var start_date = moment(event.start).format('YYYY-MM-DD HH:MM:SS');
                var end_date = moment(event.end).format('YYYY-MM-DD HH:MM:SS');
                $.ajax({
                        url: "{{ route('calendar.update', '') }}" +'/'+ id,
                        type: 'PATCH',
                        dataType: 'json',
                        data: { start_date, end_date },
                        success:function(response)
                        {
                            swal("Good job!", "Event Updated!", "success");
                        },
                        error:function(error)
                        {
                            console.log(error);
                        },                       
                    }); 
            },
            eventClick: function(event){
                var id = event.id;
                if(confirm('Are you sure, that you want to remove this event?')){
                    $.ajax({
                        url: "{{ route('calendar.destroy', '') }}" +'/'+ id,
                        type: 'DELETE',
                        dataType: 'json',
                        success:function(response)
                        {
                           $('#calendar').fullCalendar('removeEvents', response);
                        },
                        error:function(error)
                        {
                            console.log(error);
                        },                       
                    }); 
                }
            },
            displayEventTime: true,
            timeFormat: 'HH:mm',
            displayEventEnd: true,                
        });
    });
    </script>
    <script src="/js/custom.js"></script>
    <script src="/js/bootstrap/bootstrap.min.js"></script> 
</body>
</html>