<div class="form-row">
    <div class="form-group{{ $errors->has('depot_id') ? ' has-danger' : '' }} col-md-6  @if($form_type == 'ADD'){{ __('d-none') }}@endif">
        <label>{{ __('Inventory Id') }} @if( _($form_type)  == 'SHOW'){{ __(' : ') }}@endif </label>
        <input  type="text" name="depot_id" id="depot_id" class="form-control{{ $errors->has('depot_id') ? ' is-invalid' : '' }}" 
                value="@if( $form_type  == 'EDIT'){{$form_data['depot_id']? $data['depot_id']:' '}}@endif" 
                placeholder="{{ __('Inventory Id') }}" @if( _($form_type)  == 'EDIT'){{ __('Disabled') }}@endif >
        @include('alerts.feedback', ['field' => 'depot_id'])
    </div>
    <div class="form-group{{ $errors->has('depot_user_name') ? ' has-danger' : '' }} col-md-6">
        <label>{{ __('Inventory Username') }} @if( _($form_type)  == 'SHOW'){{ __(' : ') }}@endif</label>
        <input  type="text" name="depot_user_name" id="depot_user_name" class="form-control{{ $errors->has('depot_user_name') ? ' is-invalid' : '' }}"  
                value="@if( $form_type  == 'EDIT'){{$data['depot_user_name']? $data['depot_user_name']:' '}}@endif"
                placeholder="{{ __('Inventory Username') }}" Disabled>
        @include('alerts.feedback', ['field' => 'depot_user_name'])
    </div>
</div>

<div class="form-row">
    <div class="form-group{{ $errors->has('depot_erp_ref') ? ' has-danger' : '' }} col-md-6">
        <label>{{ __('Inventory ERP Reference') }} @if( _($form_type)  == 'SHOW'){{ __(' : ') }}@endif</label>
        <input  type="text" name="depot_erp_ref" id="depot_erp_ref" class="form-control{{ $errors->has('depot_erp_ref') ? ' is-invalid' : '' }}" 
                value="@if(  $form_type  == 'EDIT'){{$form_data['depot_erp_ref']? $data['depot_erp_ref']:' '}}@endif"
                placeholder="{{ __('Inventory ERP Reference') }}">
        @include('alerts.feedback', ['field' => 'depot_erp_ref'])
    </div>
    <div class="form-group{{ $errors->has('depot_status') ? ' has-danger' : '' }} col-md-6">
        <label>{{ __('Inventory Status') }} @if( _($form_type)  == 'SHOW'){{ __(' : ') }}@endif</label>
        @if($form_type == 'SHOW')
            <input  type="text" name="depot_status" id="depot_status" class="form-control{{ $errors->has('depot_status') ? ' is-invalid' : '' }}"  
                    value="@if(  $form_type  == 'EDIT'){{$form_data['depot_status']? $data['depot_status']:' '}}@endif"
                    placeholder="{{ __('Inventory Status') }}">
        @else
            <select class="form-control{{ $errors->has('depot_status') ? ' is-invalid' : '' }}" id="depot_status" name="depot_status">
                <option value=""></option>
                @if(!$depotStatus==null)
                    @foreach($depotStatus as $data)
                        <option value="{{ $data }}">{{ $data }}</option>
                    @endforeach
                @endif
            </select>
            @if($form_type == 'EDIT')
                @push('js')
                    <script>
                        $('#depot_edit_modal #depot_status').val("{{ $form_data['depot_status'] }}");
                    </script>
                @endpush
            @endif
        @endif
        @include('alerts.feedback', ['field' => 'depot_status'])
        
    </div>
</div>

<div class="form-row">
    <div class="form-group{{ $errors->has('depot_user_address') ? ' has-danger' : '' }} col-md-6">
        <label>{{ __('Inventory User Address') }} @if( _($form_type)  == 'SHOW'){{ __(' : ') }}@endif</label>
        <input  type="text" name="depot_user_address"  id="depot_user_address" class="form-control{{ $errors->has('depot_user_address') ? ' is-invalid' : '' }}" 
                value="@if( $form_type  == 'EDIT'){{$form_data['depot_user_address']? $form_data['depot_user_address']:' '}}@endif"
                placeholder="{{ __('Inventory User Address') }}">
        @include('alerts.feedback', ['field' => 'depot_user_address'])
    </div>
    <div class="form-group{{ $errors->has('depot_type') ? ' has-danger' : '' }} col-md-6">
        <label>{{ __('Inventory Type') }} @if( _($form_type)  == 'SHOW'){{ __(' : ') }}@endif</label>
        @if($form_type == 'SHOW')
            <input  type="text" name="depot_type" id="depot_type" class="form-control{{ $errors->has('depot_type') ? ' is-invalid' : '' }}"  
                    value="@if( $form_type  == 'EDIT'){{$form_data['depot_type']? $data['depot_type']:' '}}@endif" 
                    placeholder="{{ __('Inventory Type') }}">
        @else
            <select class="form-control{{ $errors->has('depot_type') ? ' is-invalid' : '' }}" id="depot_type" name="depot_type_dropdown">
                <option value=""></option>
                @if(!$depotType==null)
                    @foreach($depotType as $data)
                        <option value="{{ $data }}">{{ $data }}</option>
                    @endforeach
                @endif
            </select>
            @if($form_type == 'EDIT')
                @push('js')
                    <script>
                        $('#depot_edit_modal #depot_type').val("{{ $form_data['depot_type'] }}");
                    </script>
                @endpush
            @endif
        @endif
        @include('alerts.feedback', ['field' => 'depot_type'])
    </div>
</div>