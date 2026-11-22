export function launcher(req, res) {
  res.statusCode = 200;
  res.setHeader('Content-Type', 'text/html');
  res.end('<h1>Launcher Bridge</h1>');
}
export default launcher;