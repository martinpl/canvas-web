<x-layouts.app :title="$app->name">
      <livewire:app-name :$app />
      <br>
      @livewire("{$app->app}.settings", ['app' => new $class(request('id'))])
</x-layouts.app>