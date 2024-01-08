<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exterior Homes Design</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        .list-style-none {list-style: none}
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            grid-gap: calc(2em + 2vh) calc(1.5em + 1vmin);
            grid-auto-flow: row dense;
        }

        .card__image {
            display: block;
            max-width: 100%;
        }

        /* ==== Styles Related to the callout ==== */
        .grid .card:nth-child(14) {
            grid-column: 1/-1;
            grid-row: span 2;
            align-self: center;
        }
        @media (min-width: 712px) {
            .grid .card:nth-child(14) {
                grid-column: 2/-2;
            }
        }
        .grid .card:nth-child(14) .card__image {
            margin: 0 auto;
            -o-object-fit: cover;
            object-fit: cover;
        }
        @media (min-width: 712px) {
            .grid .card:nth-child(14) .card__image {
                height: 100%;
            }
        }

        /* ==== Additional Styles not related to grid layout ==== */
        .container {
            display: grid;
            grid-template-columns: minmax(1em, 1fr) minmax(-webkit-min-content, 1280px) minmax(1em, 1fr);
            grid-template-columns: minmax(1em, 1fr) minmax(min-content, 1280px) minmax(1em, 1fr);
            grid-template-areas: "l-gutter heading r-gutter" "l-gutter content r-gutter";
            grid-row-gap: 1em;
        }

        .grid {
            grid-area: content;
            margin-bottom: 2em;
        }

        .card__link {
            text-align: center;
            color: inherit;
            text-decoration: none;
        }

        .card__link:hover {
            color: #2d2d2d;
        }

        .card__link:hover .card__image {
            transform: scale(1.04);
            -webkit-clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 90%, 0 100%);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 90%, 0 100%);
        }

        .card__link:hover .card__text {
            transform: rotate(2deg) translate3d(3%, 1%, 0) scale(1.02);
        }

        .card__link:hover .card__price {
            transform: rotate(-2deg) translate3d(-3%, -1%, 0) scale(1.02);
        }

        .card__image {
            -webkit-clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%, 0 100%);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%, 0 100%);
            transition: transform 200ms ease-out, -webkit-clip-path 200ms ease-out;
            transition: transform 200ms ease-out, clip-path 200ms ease-out;
            transition: transform 200ms ease-out, clip-path 200ms ease-out, -webkit-clip-path 200ms ease-out;
        }

        .card__price {
            font-weight: 600;
            transition: transform 300ms ease-out;
        }

        .card__text {
            padding: 1em 0 0.5em;
            font-weight: 200;
            transition: transform 300ms ease-out;
        }

        h3 {
            grid-area: heading;
            font-size: calc(1.25rem + 2vw);
            padding: 1em 0 0.5em;
            line-height: 1.3;
            max-width: 18ex;
            text-shadow: 0 0 1em rgba(255, 255, 255, 0.5);
        }

        @media (min-width: 1000px) {
            h3 {
                font-size: 2.5rem;
            }
        }
        h3 > span {
            font-weight: 700;
        }

        footer {
            text-align: center;
            background-color: #4448;
            color: #fff;
            padding: 2em;
            margin-top: 2em;
        }

        body {
            font-family: system-ui;
            font-size: 16px;
            color: #555;
            background: url("http://localhost/TIM/images/exterior-bg.jpg") no-repeat;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            width: 120%;
            height: 40vh;
            position: absolute;
            left: -20%;
            top: -20%;
            transform: rotate(-10deg);
            background-color: #ffeb3b;
            z-index: -1;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>
        Exterior Homes <span>Design</span>
    </h3>
    <ul class="grid list-style-none">
        @foreach($homes as $home)
        <li class="card" id="card{{$home->id}}">
            <a class="card__link" href="{{url("$home->slug")}}"><img class="card__image" src="{{asset('media/uploads/exterior/'.$home->base_image)}}" />
                <div class="card__text">
                    {{$home->title}}
                    @if($home->status_id == 1)
                        <span class="badge badge-success text-uppercase">active</span>
                    @elseif($home->status_id == 0)
                        <span class="badge badge-danger text-uppercase">deactive</span>
                    @endif
                </div>
            </a>
            <a href="{{url("$home->slug")}}" class="text-dark mr-25 badge badge-info" target="_blank" style="background: green;color: #fff !important;"> Preview </a>
        </li>
        @endforeach
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.0/js/mdb.min.js"></script>

</body>
</html>
