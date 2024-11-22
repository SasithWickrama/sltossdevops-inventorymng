@extends('layouts.app', ['page' => __('UPDATE PROJECT'), 'pageSlug' => 'updateProject'])

@include('Project.projectForm', ['page_type' => 'update','title' => 'Update Project', 'btn_name' => 'Edit'])