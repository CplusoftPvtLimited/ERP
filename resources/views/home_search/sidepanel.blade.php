<div class="card p-0 m-0" style=" box-shadow:none !important;">
    <div class="card-header">
        <h6>
            Vehicle Type
        </h6>
    </div>
    <div class="card-body text-center">
        <img src="images/logo.png" alt="" width="150px">
        <p>
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Exercitationem magni architecto quisquam. Pariatur
            facilis est excepturi enim quia, impedit, architecto dolore similique itaque dolorem ullam eligendi
            suscipit, debitis repellendus voluptate.
        </p>
    </div>
</div>
<div class="card p-0 m-0" style=" box-shadow:none !important;">
    <div class="card-header">
        <h6>
            Vehicle Details
        </h6>
    </div>
    <div class="card-body">
        <div class="table">
            <thead>
                <tr>
                    <th>
                        Technical Details
                    </th>
                </tr>
            </thead>
            <table class="table-responsive m-0 p-0">
                <tbody>
                    <tr>
                        <th>Vehicle Type</th>
                        <td>{{ $type == "P" ? "Pessenger Car" : "Commercial Vehicle & Tractor" }}</td>
                    </tr>
                    <tr>
                        <th>Model Year</th>
                        <td>{{ $model_year }}</td>
                    </tr>
                    <tr>
                        <th>Capacity</th>
                        <td>{{ $cc }}</td>
                    </tr>
                    <tr>
                        <th>Valves</th>
                        <td>{{ $engine->valves }}</td>
                    </tr>
                    <tr>
                        <th>Body Style</th>
                        <td>{{ $engine->bodyStyle }}</td>
                    </tr>
                    
                    <tr>
                        <th>Engine Type</th>
                        <td>{{ $engine->engineType }}</td>
                    </tr>
                    
                    <tr>
                        <th>Fuel Type</th>
                        <td>{{ $fuel }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
