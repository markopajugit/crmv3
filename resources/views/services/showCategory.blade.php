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

                        <button type="button" class="btn deleteService" data-servicecategoryid="{{ $service_category->id }}">
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
                            <td style="width:50%"><strong>Name:</strong></td>
                            <td id="currentName">{{ $service_category->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>


        </tbody></table></form>`);

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

            $.ajax({
                type: 'PUT',
                url: "/services/category/"+{{$service_category->id}},
                data: {name: name},
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
            if (window.confirm("Delete Service Category?")) {
                var servicecategoryid = $(this).data('servicecategoryid');
                $.ajax({
                    type: 'DELETE',
                    url: "/services/category/"+servicecategoryid,
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
                        alert("Error, The service category is in use");
                    }
                });
            }
        });
    </script>

@endsection
