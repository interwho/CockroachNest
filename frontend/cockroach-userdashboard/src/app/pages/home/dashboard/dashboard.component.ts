import { Component, OnInit } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';

declare function require(path: string): any;

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {

  constructor(private http: Http) { }

  ngOnInit() {
    require('../../../../assets/js/charts.js')();
  }

  getNodes(){
    this.http.get('https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/2.5_week.geojsonp').subscribe(
      data =>{
        console.log('test');
      }
    )
  }

}
