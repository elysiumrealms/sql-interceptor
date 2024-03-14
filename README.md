# SQLInterceptor

## Description

SQLInterceptor is a tool for intercepting and modifying SQL queries. It can be used to monitor and adjust SQL queries being executed within an application.

## Features

- Intercept SQL queries

  ```php
  use Elysiumrealms\SQLInterceptor\SQLInterceptor;

  class TaskExcelExporter extends AbstractExporter
  {
      public function export()
      {
          $queries =  SQLInterceptor::intercept(function () {

              // Complex logic which fetch from database connection
              // and cannot be pass into Laravel Job

          })->queries();

          // Pass into Laravel Job execyte async
          dispatch(new AsyncQueryJob($queries));
      }
  }
  ```
