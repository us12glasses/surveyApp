@extends(backpack_view('blank'))

@section('content')
<div class="container">
    <h2>Survey Reports</h2>
    
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports') }}">
                <div class="row">
                    <!-- Question Type Filter -->
                    <div class="col-md-3">
                        <label>Question Type</label>
                        <select name="question_type" class="form-control">
                            <option value="">All Types</option>
                            @foreach($questionTypes as $type => $label)
                                <option value="{{ $type }}" {{ $filters['question_type'] == $type ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="date" 
                            name="start_date" 
                            value="{{ $filters['start_date'] ?? '' }}" 
                            class="form-control" 
                            max="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="date" 
                            name="end_date" 
                            value="{{ $filters['end_date'] ?? '' }}" 
                            class="form-control" 
                            max="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Answer Contains -->
                    <div class="col-md-3">
                        <label>Answer Contains</label>
                        <input type="text" name="answer" 
                               value="{{ $filters['answer'] }}" 
                               class="form-control">
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-filter"></i> Apply Filters
                    </button>
                    <a href="{{ route('export.responses', request()->query()) }}" class="btn btn-success">
                        <i class="la la-download"></i> Export
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Add your custom report content here -->
</div>
@endsection