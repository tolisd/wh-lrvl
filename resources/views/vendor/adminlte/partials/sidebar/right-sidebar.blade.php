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
                @elseif(\Auth::user()->user_type == 'normal_user')
                    <strong>Απλός Χρήστης</strong>
                @endif
            </p>
        </li>

        <li>
            <p>
                @if(\Auth::user()->photo_url)
                    <!-- <img src="{{ asset('storage/images/profile/' . \Auth::user()->photo_url) }}" alt=""></img> -->
                    <img src="{{ \Auth::user()->photo_url }}" alt=""></img>
                @endif
            </p>
        </li>

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
