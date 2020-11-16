<aside class="control-sidebar control-sidebar-{{config('adminlte.right_sidebar_theme')}}">
    @yield('right-sidebar')

    <style>
        img{
            max-width: 30%;
            max-height: 30%;
        }
    </style>

    <ul>
        <li>
            <p><strong>{{ \Auth::user()->name }}</strong></p>
        </li>

        <li>
            <p>
                @if(\Auth::user()->user_type == 'super_admin')
                    <strong>Διαχειριστής</strong>
                @elseif(\Auth::user()->user_type == 'company_ceo')
                    <strong>Διευθυντής</strong>
                @elseif(\Auth::user()->user_type == 'warehouse_foreman')
                    <strong>Προϊστάμενος Αποθήκης</strong>
                @elseif(\Auth::user()->user_type == 'accountant')
                    <strong>Λογιστήριο</strong>
                @elseif(\Auth::user()->user_type == 'warehouse_worker')
                    <strong>Αποθηκάριος</strong>
                @elseif(\Auth::user()->user_type == 'technician')
                    <strong>Τεχνίτης</strong>
                @elseif(\Auth::user()->user_type == 'normal_user')
                    <strong>Απλός Χρήστης</strong>
                @endif
            </p>
        </li>


                @if(\Auth::user()->photo_url)

                    @if(\Auth::user()->user_type == 'super_admin')
                    <li><p>
                        <!-- <img src="{{ asset('storage/images/profile/' . \Auth::user()->photo_url) }}" alt=""></img> -->
                        <img src="{{ route('admin.user.show.userpic', ['photo' => basename(\Auth::user()->photo_url) ]) }}" alt=""></img>
                    </p></li>
                    @endif

                    @if(\Auth::user()->user_type == 'company_ceo')
                    <li><p>
                        <img src="{{ route('manager.user.show.userpic', ['photo' => basename(\Auth::user()->photo_url) ]) }}" alt=""></img>
                    </p></li>
                    @endif

                    @if(\Auth::user()->user_type == 'warehouse_foreman')
                    <li><p>
                        <img src="{{ route('foreman.user.show.userpic', ['photo' => basename(\Auth::user()->photo_url) ]) }}" alt=""></img>
                    </p></li>
                    @endif

                    @if(\Auth::user()->user_type == 'warehouse_worker')
                    <li><p>
                        <img src="{{ route('worker.user.show.userpic', ['photo' => basename(\Auth::user()->photo_url) ]) }}" alt=""></img>
                    </p></li>
                    @endif

                    @if(\Auth::user()->user_type == 'accountant')
                    <li><p>
                        <img src="{{ route('accountant.user.show.userpic', ['photo' => basename(\Auth::user()->photo_url) ]) }}" alt=""></img>
                    </p></li>
                    @endif

                    @if(\Auth::user()->user_type == 'technician')
                    <li><p>
                        <img src="{{ route('technician.user.show.userpic', ['photo' => basename(\Auth::user()->photo_url) ]) }}" alt=""></img>
                    </p></li>
                    @endif

                    @if(\Auth::user()->user_type == 'normal_user')
                    <li><p>
                        <img src="{{ route('user.user.show.userpic', ['photo' => basename(\Auth::user()->photo_url) ]) }}" alt=""></img>
                    </p></li>
                    @endif

                @endif


        <li>
            <p>
                @php
                    $mytime = Carbon\Carbon::now();
                @endphp
                {{ $mytime->toDateTimeString() }}
            </p>
        </li>
    </ul>

</aside>
