@extends('layouts.app', ['page' => __('UPDATE MAJOR PROJECT'), 'pageSlug' => 'updateMajorProject'])

@include('majorproject.majorProject', ['page_type' => 'update','title' => __('Update Major Project'), 'btn_name' => __('Edit')])
