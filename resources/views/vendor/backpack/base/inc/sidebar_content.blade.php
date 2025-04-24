{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('question') }}"><i class="nav-icon la la-question"></i> Questions</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('response') }}"><i class="nav-icon la la-comment"></i> Responses</a></li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('reports') }}">
        <i class="nav-icon la la-chart-bar"></i> Reports
    </a>
</li>