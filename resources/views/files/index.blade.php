@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Documents</h1></div>
        <!--<div class="col-6 col-sm-3 m-2"><a class="btn btn-success" href="{{ route('companies.create') }}" style="color:white;"> Create New Company</a></div>-->
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <form action="/search/documents/" class="header-search">
        <input id="search" class="form-control mr-sm-2" style="width:45%; display: inline-block;" type="search" autocomplete="off" placeholder="Search" name="s" aria-label="Search" value="{{ request()->get('s') }}">
        <div id="searchResults" style=" display:none;position: absolute;padding: 10px;list-style: none;"></div>
        <select name="category" id="categoryName" style="width:30%; display: inline-block;" class="form-control">
            <option value="all" @if(request()->get('category') == 'all') selected @endif>All</option>
            <option value="archived" @if(request()->get('category') == 'archived') selected @endif>Archived</option>
            <option value="general" @if(request()->get('category') == 'general') selected @endif>General</option>
            <option value="virtualoffice" @if(request()->get('category') == 'virtualoffice') selected @endif>Virtual Office</option>
        </select>
        <button class="btn" style="width: 20%;top: -2px;position: relative;" type="submit">Search</button>
    </form>

                        <td><a href="/orders/{{$doc->order_id}}">View Order</a></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endif

        @if(request()->get('category') == 'virtualoffice')
            @foreach($virtualoffice as $doc)
                <tr>
                    <td>Archive</td>
                    <td>{{$doc->name}}</td>
                    <td>{{$doc->archive_nr}}</td>
                    @if($doc->person_id)
                        <td><a href="/persons/{{$doc->person_id}}">View Person</a></td>
                    @elseif($doc->company_id)
                        <td><a href="/companies/{{$doc->company_id}}">View Company</a></td>
                    @elseif($doc->order_id)
                        <td><a href="/orders/{{$doc->order_id}}">View Order</a></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endif

        @if(request()->get('category') == 'general')
            @foreach($general as $doc)
                <tr>
                    <td>General</td>
                    <td>{{$doc->name}}</td>
                    <td> - </td>
                    @if($doc->person_id)
                        <td><a href="/persons/{{$doc->person_id}}">View Person</a></td>
                    @elseif($doc->company_id)
                        <td><a href="/companies/{{$doc->company_id}}">View Company</a></td>
                    @elseif($doc->order_id)
                        <td><a href="/orders/{{$doc->order_id}}">View Order</a></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endif

        @if(request()->get('category') == 'all')
            @foreach($general as $doc)
                <tr>
                    <td>General</td>
                    <td>{{$doc->name}}</td>
                    <td> - </td>
                    @if($doc->person_id)
                        <td><a href="/persons/{{$doc->person_id}}">View Person</a></td>
                    @elseif($doc->company_id)
                        <td><a href="/companies/{{$doc->company_id}}">View Company</a></td>
                    @elseif($doc->order_id)
                        <td><a href="/orders/{{$doc->order_id}}">View Order</a></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
            @foreach($virtualoffice as $doc)
                <tr>
                    <td>Virtual Office</td>
                    <td>{{$doc->name}}</td>
                    <td> - </td>
                    @if($doc->person_id)
                        <td><a href="/persons/{{$doc->person_id}}">View Person</a></td>
                    @elseif($doc->company_id)
                        <td><a href="/companies/{{$doc->company_id}}">View Company</a></td>
                    @elseif($doc->order_id)
                        <td><a href="/orders/{{$doc->order_id}}">View Order</a></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
            @foreach($archived as $doc)
                <tr>
                    <td>Archive</td>
                    <td>{{$doc->name}}</td>
                    <td>{{$doc->archive_nr}}</td>
                    @if($doc->person_id)
                        <td><a href="/persons/{{$doc->person_id}}">View Person</a></td>
                    @elseif($doc->company_id)
                        <td><a href="/companies/{{$doc->company_id}}">View Company</a></td>
                    @elseif($doc->order_id)
                        <td><a href="/orders/{{$doc->order_id}}">View Order</a></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

@endsection
