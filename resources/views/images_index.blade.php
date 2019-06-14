@extends('layouts.app')

@section('content')
    <card-component>
        <template v-slot:header>
            <h1>Disk usage overview</h1>
        </template>
        <template v-slot:body>
            Total size: {{ round($data_used/1048576, 2) }}MB ({{number_format($data_used)}}B) <br>
            No of files: {{$total_images}}
        </template>
    </card-component>
    <card-component>
        <template v-slot:header>
            <h1>Disk usage compositions</h1>
        </template>

        <template v-slot:body>
            @if($total_images)
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Type</th>
                        <th scope="col">No of files</th>
                        <th scope="col">Size</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($counter as $type => $count)
                        <tr>
                            <td>{{ $type }}</td>
                            <td>{{ $count }}</td>
                            <td>{{ round($size_by_type[$type]/1048576, 2) }}MB ({{number_format($size_by_type[$type])}}B)</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No Data
            @endif

        </template>
    </card-component>

@endsection
