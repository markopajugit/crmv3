@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">Details</div>
                    <div class="panel-heading__button">

                        <button type="button" class="btn editDetails">
                            <i class="fa-solid fa-pen-to-square"></i>Edit
                        </button>

                        <button type="button" class="btn deleteService" data-serviceid="{{ $service->id }}">
                            <i class="fa-solid fa-pen-to-square"></i>Delete
                        </button>

                        <button type="button" class="btn saveDetails" style="display: none;">
                            <i class="fa-solid fa-check"></i>Save
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td style="width:50%"><strong>Category:</strong></td>
                            <td id="currentCategory" data-value="{{ $service->service_category->id }}">{{ $service->service_category->name }}</td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Name:</strong></td>
                            <td id="currentName">{{ $service->name }}</td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Cost:</strong></td>
                            <td id="currentCost">{{ $service->cost }}</td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Type:</strong></td>
                            <td id="currentType">{{ $service->type }}</td>
                        </tr>
                        @if($service->type == 'Reaccuring')
                        <tr>
                            <td style="width:50%"><strong>Reaccuring Frequency:</strong></td>
                            <td>{{ $service->reaccuring_frequency }} months</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>


                <tr>
                <td style="width:50%"><strong>Category:</strong></td>
            <td>
            <select name="service_category_id" id="service_category_id">

                @foreach ($service_categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </td>
        </tr>
        <tr>
            <td style="width:50%"><strong>Name:</strong></td>
        <td><input type="text" name="name" id="name" value="`+currentName+`"></td>
            </tr>
            <tr>
                <td style="width:50%"><strong>Cost:</strong></td>
                <td><input type="text" name="cost" id="cost" value="`+currentCost+`"></td>
            </tr>
            <tr>
                <td style="width:50%"><strong>Type:</strong></td>
                <td>
                    <select name="type" id="type">
                        <option value="Regular">Regular</option>
                        <option value="Reaccuring">Reaccuring</option>
                    </select>
                </td>
            </tr>

            <tr style="display: none;" id="reaccuring-frequency__select">
                <td style="width:50%"><strong>Service Length:</strong></td>
                <td>
                    <select name="reaccuring_frequency" id="reaccuring_frequency">
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="12">12 Months</option>
                    </select>
                </td>
            </tr>
        </tbody></table></form>`);

        //$('#service_category_id').
            $('#service_category_id').val(currentCategoryVal);

        });

        $(document).on('change', '#type', function (e) {
            if($(this).val() === "Reaccuring"){
                $('#reaccuring-frequency__select').show();
            } else {
                $('#reaccuring-frequency__select').hide();
            }
        });

        $('.panel-details').on('click', '.panel-heading__button .saveDetails', function(){
            var name = $("#name").val();
            var cost = $("#cost").val();
            var service_category_id = $("#service_category_id").val();
            var type = $("#type").val();
            var reaccuring_frequency = $("#reaccuring_frequency").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('services.update',$service->id) }}",
                data: {name: name, cost: cost, type: type, reaccuring_frequency: reaccuring_frequency, service_category_id : service_category_id},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.deleteService').on('click', function(e){
            e.preventDefault();
            if (window.confirm("Delete Service?")) {
                var personId = $(this).data('serviceid');
                $.ajax({
                    type: 'DELETE',
                    url: "/services/"+personId,
                    success: function (data) {
                        console.log(data.message);
                        if ($.isEmptyObject(data.message)) {
                            window.location.replace("/services");
                        } else {
                            alert(data.message);
                            printErrorMsg(data.message);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Error, The service is in use");
                    }
                });
            }
        });
    </script>

@endsection
