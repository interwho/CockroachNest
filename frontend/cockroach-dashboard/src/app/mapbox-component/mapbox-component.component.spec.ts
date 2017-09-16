import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MapboxComponentComponent } from './mapbox-component.component';

describe('MapboxComponentComponent', () => {
  let component: MapboxComponentComponent;
  let fixture: ComponentFixture<MapboxComponentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MapboxComponentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MapboxComponentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
