@extends('layouts.app', ['page' => __('CREATE Job'), 'pageSlug' => 'createProject'])

@include('Project.projectForm', ['page_type' => 'create','title' => __('Create New Job'), 'btn_name' => __('Create')])
