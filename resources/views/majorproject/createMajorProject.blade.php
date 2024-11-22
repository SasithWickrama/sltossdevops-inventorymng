@extends('layouts.app', ['page' => __('CREATE MAJOR PROJECT'), 'pageSlug' => 'createMajorProject'])

@include('majorproject.majorProject', ['page_type' => 'create','title' => __('Create New Major Project'), 'btn_name' => __('Create')])
