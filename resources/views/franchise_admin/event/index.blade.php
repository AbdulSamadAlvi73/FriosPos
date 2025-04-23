@extends('layouts.app')
@section('content')
    <div class="content-body default-height p-5 mt-5">
        <div class="container-fluid rounded border p-5 bg-white">
            <div class="d-flex justify-content-between">
                <h1 style="font-size: 28px; font-weight: bold">
                    Events
                </h1>

                <a href="{{ route('franchise.events.create') }}" class="btn btn-primary">
                    Create Event
                </a>
            </div>
            <table class="table customer-table display mb-4 fs-14 card-table dataTable no-footer" id="dynamicTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $key => $event)
                        <tr>
                            <td>
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $event->name }}
                            </td>
                            <td>
                                {{ date('d M Y', strtotime($event->start_date)) }}
                            </td>
                            <td>
                                {{ date('d M Y', strtotime($event->end_date)) }}
                            </td>
                            <td class="d-flex justify-content-around">
                                <a title="compare" href="{{ route('franchise.events.compare', ['event' => $event]) }}" class="text-success">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                                {{-- <button class="text-info">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="text-warning">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="text-danger">
                                    <i class="fa fa-trash"></i>
                                </button> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <!-- Modal trigger button -->
        {{-- <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalId">
            Launch
        </button> --}}

        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        {{-- <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Event required items
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table customer-table display mb-4 fs-14 card-table dataTable no-footer" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Required Quantity</th>
                                    <th>Available Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $key => $event)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            {{ $event->name }}
                                        </td>
                                        <td>
                                            {{ date('d M Y', strtotime($event->start_date)) }}
                                        </td>
                                        <td>
                                            {{ date('d M Y', strtotime($event->end_date)) }}
                                        </td>
                                        <td class="d-flex justify-content-around">
                                            <a href="{{ route('franchise.events.compare', ['event' => $event]) }}" class="text-success">
                                                <i class="fas fa-exchange-alt"></i>
                                            </a>
                                            <button class="text-info">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="text-warning">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button class="text-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-black text-dark" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
@endsection
