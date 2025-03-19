@extends('emails.layouts.main')

@section('title', __('Account Status Update'))

@section('header', __('Account Status Update'))

@section('content')
    <p>{{__('Hello')}},</p>
    
    <p>{{__('We would like to inform you that your account status at Cargo Haven has been updated to:')}} <span class="status">{{ $rr->rr_status->name() }}</span></p>
    
    <p>{{__('If you have any questions about this change or need further assistance, please contact our support team.')}}</p>
    
    <p>{{__('Thank you for using Cargo Haven!')}}</p>
@endsection