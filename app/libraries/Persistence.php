<?php namespace App\Libraries;
interface Persistence {

        function persist($data);

        function retrieve($ids);

        function retrieveAll();
}