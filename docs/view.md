# View

## Variable
```
{$var}
{$obj->var}
{$obj->method()}
```

## Method
```
{\time()}
```

## Syntax
```
@if $user
  <span>{$user->name}</span>
@else
  <span>no user</span>
@end

@foreach $items => $item
  <span>{$item}</span>
@end

@for $i = 0; $i < 10; $i++
  <span>step {$i}</span>
@end
```

## Rendering

```
@yield

@render '/path/to/filename'

@partial 'key'
```
