@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <!-- Striped rows -->
                    <div class="col-md-12 my-4">
                        <h2 class="h4 mb-1">Grouped header & rows</h2>
                        <p class="mb-4">Customized table based on Bootstrap with additional elements and more functions
                        </p>
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="toolbar row mb-3">
                                    <div class="col">
                                        <form class="form-inline">
                                            <div class="form-row">
                                                <div class="form-group col-auto">
                                                    <label for="search" class="sr-only">Search</label>
                                                    <input type="text" class="form-control" id="search"
                                                        value="" placeholder="Search">
                                                </div>
                                                <div class="form-group col-auto ml-3">
                                                    <label class="my-1 mr-2 sr-only"
                                                        for="inlineFormCustomSelectPref">Status</label>
                                                    <select class="custom-select my-1 mr-sm-2"
                                                        id="inlineFormCustomSelectPref">
                                                        <option selected>Choose...</option>
                                                        <option value="1">Processing</option>
                                                        <option value="2">Success</option>
                                                        <option value="3">Pending</option>
                                                        <option value="3">Hold</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col ml-auto">
                                        <div class="dropdown float-right">
                                            <button class="btn btn-primary float-right ml-3" type="button">Add more
                                                +</button>
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="actionMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"> Action </button>
                                            <div class="dropdown-menu" aria-labelledby="actionMenuButton">
                                                <a class="dropdown-item" href="#">Export</a>
                                                <a class="dropdown-item" href="#">Delete</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- table -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr role="row">
                                            <th colspan="3">Orders</th>
                                            <th colspan="4">Billing</th>
                                            <th colspan="3">State</th>
                                        </tr>
                                        <tr role="row">
                                            <th>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="all">
                                                    <label class="custom-control-label" for="all"></label>
                                                </div>
                                            </th>
                                            <th>ID</th>
                                            <th>Purchase Date</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Tracking #</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="4574">
                                                    <label class="custom-control-label" for="4574"></label>
                                                </div>
                                            </td>
                                            <td>4574</td>
                                            <td>2019-09-11 10:22:04</td>
                                            <td>Kitra Knapp</td>
                                            <td>(132) 339-7423</td>
                                            <td>P.O. Box 944, 4739 Suspendisse Road</td>
                                            <td>$68.79</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td></td>
                                            <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Remove</a>
                                                    <a class="dropdown-item" href="#">Assign</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="2132">
                                                    <label class="custom-control-label" for="2132"></label>
                                                </div>
                                            </td>
                                            <td>2132</td>
                                            <td>2019-08-23 12:28:40</td>
                                            <td>Blake Orr</td>
                                            <td>(257) 565-4706</td>
                                            <td>P.O. Box 939, 9156 Sollicitudin St.</td>
                                            <td>$84.24</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td></td>
                                            <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Remove</a>
                                                    <a class="dropdown-item" href="#">Assign</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr role="group" class="bg-light">
                                            <td colspan="10"><strong>Shipped</strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="2576">
                                                    <label class="custom-control-label" for="2576"></label>
                                                </div>
                                            </td>
                                            <td>2576</td>
                                            <td>2020-07-14 17:18:27</td>
                                            <td>Amber Rice</td>
                                            <td>(791) 898-8806</td>
                                            <td>P.O. Box 724, 3385 Vel Ave</td>
                                            <td>$37.00</td>
                                            <td><span class="badge badge-success">Success</span></td>
                                            <td>487385-5144</td>
                                            <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Remove</a>
                                                    <a class="dropdown-item" href="#">Assign</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="2397">
                                                    <label class="custom-control-label" for="2397"></label>
                                                </div>
                                            </td>
                                            <td>2397</td>
                                            <td>2020-12-25 13:35:39</td>
                                            <td>Fletcher Petty</td>
                                            <td>(115) 625-5813</td>
                                            <td>8907 Orci St.</td>
                                            <td>$75.69</td>
                                            <td><span class="badge badge-success">Success</span></td>
                                            <td>039606-5955</td>
                                            <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Remove</a>
                                                    <a class="dropdown-item" href="#">Assign</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr role="group" class="bg-light">
                                            <td colspan="10"><strong>Return</strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="4028">
                                                    <label class="custom-control-label" for="4028"></label>
                                                </div>
                                            </td>
                                            <td>4028</td>
                                            <td>2021-04-11 22:33:47</td>
                                            <td>Kasimir Carr</td>
                                            <td>(266) 991-0479</td>
                                            <td>489-624 Quis St.</td>
                                            <td>$86.89</td>
                                            <td><span class="badge badge-danger">Hold</span></td>
                                            <td></td>
                                            <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Remove</a>
                                                    <a class="dropdown-item" href="#">Assign</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="3782">
                                                    <label class="custom-control-label" for="3782"></label>
                                                </div>
                                            </td>
                                            <td>3782</td>
                                            <td>2020-03-01 10:44:03</td>
                                            <td>Daria Frank</td>
                                            <td>(599) 361-7999</td>
                                            <td>Ap #875-5778 Massa. Av.</td>
                                            <td>$38.04</td>
                                            <td><span class="badge badge-primary">Processing</span></td>
                                            <td>072140-0703</td>
                                            <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Remove</a>
                                                    <a class="dropdown-item" href="#">Assign</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="3881">
                                                    <label class="custom-control-label" for="3881"></label>
                                                </div>
                                            </td>
                                            <td>3881</td>
                                            <td>2019-12-17 00:40:24</td>
                                            <td>Herrod Byrd</td>
                                            <td>(878) 901-7269</td>
                                            <td>P.O. Box 107, 3720 Vitae, Ave</td>
                                            <td>$73.38</td>
                                            <td><span class="badge badge-primary">Processing</span></td>
                                            <td>047336-4370</td>
                                            <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Remove</a>
                                                    <a class="dropdown-item" href="#">Assign</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <nav aria-label="Table Paging" class="mb-0 text-muted">
                                    <ul class="pagination justify-content-end mb-0">
                                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div> <!-- simple table -->
                </div> <!-- end section -->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
@endsection
