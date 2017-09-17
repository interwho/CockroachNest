// import * as http from 'http';
import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import 'rxjs/add/operator/map';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class CockroachnodeService {

  constructor(private http: Http) { }

  getNodes() {
    return this.http.get('http://97.107.133.58/status').map((res: Response) => res.json());
  }



}
