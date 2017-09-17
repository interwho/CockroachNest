import { TestBed, inject } from '@angular/core/testing';

import { CockroachnodeService } from './cockroachnode.service';

describe('CockroachnodeService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CockroachnodeService]
    });
  });

  it('should be created', inject([CockroachnodeService], (service: CockroachnodeService) => {
    expect(service).toBeTruthy();
  }));
});
