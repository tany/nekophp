<?php

print "\n";
print "[ {$this->type} ] {$this->title}\n";

foreach ($this->views as $view) {
  print "\n";
  print str_repeat('-', 50) . "\n";
  print "## {$view['name']}\n\n";

  foreach ($view['data'] as $key => $data) {
    if (isset($data['css'])) {
      printf("% 4s: %s\n", $data['key'], $data['val']);
    } else {
      printf("%s\n", $data['val']);
    }
  }
}
