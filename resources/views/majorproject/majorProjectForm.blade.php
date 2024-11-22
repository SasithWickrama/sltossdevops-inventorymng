<div class="form-row">
    <div class="form-group{{ $errors->has('major_doc_pros_id') ? ' has-danger' : '' }} col-md-4">
        <label class="h6">{{ __('Project ID') }} </label>
        <input type="text" id="major_doc_pros_id" name="major_doc_pros_id" class="form-control{{ $errors->has('major_doc_pros_id') ? ' is-invalid' : '' }}"  value="" placeholder="{{ __('Project Id') }}">
        @include('alerts.feedback', ['field' => 'major_doc_pros_id'])
    </div>
    <div class="form-group{{ $errors->has('major_pros_name') ? ' has-danger' : '' }} col-md-4">
        <label class="h6">{{ __('Project Name') }} </label>
        <input type="text" name="major_pros_name" id="major_pros_name" class="form-control{{ $errors->has('major_pros_name') ? ' is-invalid' : '' }}"  value="" placeholder="{{ __('Project Name') }}">
        @include('alerts.feedback', ['field' => 'major_pros_name'])
    </div>
    <div class="form-group{{ $errors->has('major_pros_svtype') ? ' has-danger' : '' }} col-md-4">
        <label class="h6">{{ __('Service Type') }}  </label>
        <select class="form-control{{ $errors->has('major_pros_svtype') ? ' is-invalid' : '' }}" id="major_pros_svtype" name="major_pros_svtype">
            <option value=""></option>
            @if(!$serviceType==null)
                @foreach($serviceType as $data)
                    <option value="{{ $data }}">{{ $data }}</option>
                @endforeach
            @endif
        </select>
        @include('alerts.feedback', ['field' => 'major_pros_svtype'])
    </div>
</div>

<div class="form-row">
    @if($page_type == 'update' )
    <div class="form-group{{ $errors->has('major_pros_status') ? ' has-danger' : '' }} col-md-4">
        <label class="h6">{{ __('Project Status') }} </label>
        <select class="form-control{{ $errors->has('major_pros_status') ? ' is-invalid' : '' }}" id="major_pros_status" name="major_pros_status">
            <option value=""></option>
            @if(!$ProjectStatus==null)
                @foreach($ProjectStatus as $data)
                    <option value="{{ $data }}">{{ $data }}</option>
                @endforeach
            @endif
        </select>
        @include('alerts.feedback', ['field' => 'major_pros_status'])
    </div>
    @endif
    <div class="form-group{{ $errors->has('major_pros_target_enddate') ? ' has-danger' : '' }} col-md-4">
        <label class="h6">{{ __('Target End Date') }} </label>
        <input type="text" id="major_pros_target_enddate" name="major_pros_target_enddate" class="form-control{{ $errors->has('major_pros_target_enddate') ? ' is-invalid' : '' }}"  value="" placeholder="{{ __('Target End Date') }}">
        @include('alerts.feedback', ['field' => 'major_pros_target_enddate'])
    </div>
</div>