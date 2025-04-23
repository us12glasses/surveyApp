<!DOCTYPE html>
<html>
<head>
  <title>Survey</title>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Customer Satisfaction Survey</h1>
    </div>

  <div class="container mt-5">
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="/submit-survey">
      @csrf
      @foreach($questions as $question)
        <div class="card mb-3">
          <div class="card-body">
          <label class="form-label">{{ $question->question_text }} <span class="text-danger">*</span></label>

            <!-- Text Answer -->
            @if($question->type === 'text')
              <input type="text" class="form-control" name="answers[{{ $question->id }}]" required>

            <!-- Multiple Choice (Radio Buttons) -->
        @elseif($question->type === 'multiple_choice')
          <div class="d-flex flex-column gap-2">
            @foreach(json_decode($question->options) as $option)
              <div class="form-check">
                <input 
                  type="radio" 
                  class="form-check-input" 
                  name="answers[{{ $question->id }}]" 
                  id="option_{{ $question->id }}_{{ $loop->index }}" 
                  value="{{ $option }}" 
                  required
                >
                <label class="form-check-label" for="option_{{ $question->id }}_{{ $loop->index }}">
                  {{ $option }}
                </label>
              </div>
            @endforeach
          </div>

            <!-- Rating (1-5) -->
            @elseif($question->type === 'rating')
              <div class="mt-2">
                <div class="d-flex gap-3"> <!-- Horizontal layout -->
                  @for($i = 1; $i <= 5; $i++)
                    <div class="text-center"> <!-- Center-align each number + label -->
                      <div>
                        <input 
                          type="radio" 
                          class="btn-check" 
                          name="answers[{{ $question->id }}]" 
                          id="rating{{ $question->id }}_{{ $i }}" 
                          value="{{ $i }}"
                          required
                        >
                        <label 
                          class="btn btn-outline-primary rounded-circle" 
                          for="rating{{ $question->id }}_{{ $i }}"
                          style="width: 40px; height: 40px;"> <!-- Fixed size for consistency -->
                          {{ $i }}
                        </label>
                      </div>
                      <!-- Label (e.g., "Disappointing") -->
                      @if(isset($question->labels) && $label = json_decode($question->labels)->{$i} ?? null)
                        <small class="rating-label d-block mx-auto">{{ $label }}</small>
                      @endif
                    </div>
                  @endfor
                </div>
              </div>
            @endif <!-- Close the rating conditional here -->

          </div>
        </div>
      @endforeach
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</body>
</html>
