@extends('layouts.master')

@section('title', 'Bolknoms API')

@section('content')
    <div class="apidocs">
        <h1>Bolknoms API</h1>
        <p>
            Bolknoms has an API which you can use to automate certain actions.
        </p>
        <p class="notification warning">
            The API is in beta and can change at any time. For questions, requests and support, reach out to <a href="mailto:{{ config('mail.admin-mail') }}">{{ config('mail.admin-mail') }}</a>.
        </p>

        <h2>Tech</h2>
        <p>
            Bolknoms is a <a href="https://en.wikipedia.org/wiki/Representational_state_transfer">REST</a> API with HATEOAS. The starting point is <a href="https://noms.debolk.nl/api">https://noms.debolk.nl/api</a>. Functions of the API are discoverable through the HATEOAS links and OPTIONS requests. The main functionality is also described below.
        </p>

        <h2>Authentication</h2>
        <p>
            You cannot access the API using your regular credentials, but have to use your personal access token. You have one token that you can reset on <a href="{{ route('profile') }}">your profile</a>. You can view the token only once, but you can always reset it to get a new one. Resetting the token invalidates your current token. Tokens do not expire on their own.
        </p>
        <p>Treat your token as if it were your password. Anyone who has your token, can do anything you can do in Bolknoms (as long as the API supports it). There are no limited scopes on tokens.</p>

        <h2>Versioning</h2>
        <p>
            You <strong>must</strong> send a correct Accept-header indicating the version of the API when making requests. The only header currently in use is:
        </p>
        <code>
            Accept: application/vnd.bolknoms.v1+json
        </code>

        <h2>Functionality</h2>
        <p>
            Currently you can use the Bolknoms to:
        </p>
        <ul>
            <li>Get a list of upcoming meals (GET /api/meals/upcoming). This is the same list as you see on the homepage when logged in.</li>
            <li>Register for a meal (POST /api/meals/{id}/registrations).</li>
            <li>Cancel a registration (DELETE /api/meals/{id}/registrations/{id}).</li>
        </ul>
        <p class="notification warning">
            These paths are examples and should not be relied on. Follow the HATEOAS links in the API response to make requests instead of constructing paths by hand.
        </p>

        <h2>Errors</h2>
        <p>When things go wrong, Bolknoms will return errors in the JSON:API format including a code and title:</p>
        <code>
            {
                "errors": [
                    {
                        "code": "meal_missing",
                        "title": "Deze maaltijd bestaat niet (meer)"
                    }
                ]
            }
        </code>
        <p>An error response will have a HTTP 4XX or 5XX status code. The code is intended to distinguish different errors and handle them in your code. The title field is often in Dutch and can be shown directly to your end-users. For some low-level errors that appear primarily while building your client, titles are given in English, like missing the correct Accept-header, etc.</p>
        <table>
            <thead><tr>
                <th class="code">HTTP status</th>
                <th>Code</th>
                <th>Explanation</th>
            </tr></thead>
            <tbody>
                <tr>
                    <td class="code">406 Not Acceptable</td>
                    <td class="code">accepts_header_missing</td>
                    <td>You must send an Accept-header, see "Versioning"</td>
                </tr>
                <tr>
                    <td class="code">406 Not Acceptable</td>
                    <td class="code">accepts_header_unsupported</td>
                    <td>The Accept-header specifies an non-existent API version</td>
                </tr>
                <tr>
                    <td class="code">404 Not Found</td>
                    <td class="code">meal_not_found</td>
                    <td>The meal cannot be found. The meal id can be wrong, or the meal has been deleted.</td>
                </tr>
                <tr>
                    <td class="code">400 Bad Request</td>
                    <td class="code">input_invalid</td>
                    <td>You're missing required parameters. The title will have more details. Currently unused.</td>
                </tr>
                <tr>
                    <td class="code">400 Bad Request</td>
                    <td class="code">meal_deadline_expired</td>
                    <td>The registration deadline for this meal has passed. You cannot register or deregister from this meal.</td>
                </tr>
                <tr>
                    <td class="code">400 Bad Request</td>
                    <td class="code">user_blocked</td>
                    <td>The current user is blocked from Bolknoms and cannot register for meals. Contact the board: Jakob will <em>not</em> unblock you.</td>
                </tr>
                <tr>
                    <td class="code">400 Bad Request</td>
                    <td class="code">double_registration</td>
                    <td>You're attempting to register for a meal, but you are already registered for this meal.</td>
                </tr>
                <tr>
                    <td class="code">400 Bad Request</td>
                    <td class="code">capacity_exceeded</td>
                    <td>There is a limit to the number of registrations to this meal. That limit has been exceeded and you cannot register. The limit is included in the data of the meal, named "capacity". Null means no limit.</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
