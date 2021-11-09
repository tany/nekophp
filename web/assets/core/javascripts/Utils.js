export default class Utils {

  static benchmark(closure, times = 30000) {
    const start = Date.now();
    for (let i = 0; i < times; i += 1) closure();
    const elapsed = Date.now() - start;

    console.debug(`${elapsed} ms (${times} times)`);
  }
}
