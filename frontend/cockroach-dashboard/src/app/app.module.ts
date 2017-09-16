import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { MapboxComponentComponent } from './mapbox-component/mapbox-component.component';

@NgModule({
  declarations: [
    AppComponent,
    MapboxComponentComponent,
  ],
  imports: [
    BrowserModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
